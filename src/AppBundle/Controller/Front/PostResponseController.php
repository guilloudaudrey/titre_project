<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostResponse;
use AppBundle\Event\PostResponseVoteEvent;
use AppBundle\Exception\PostClosedException;
use AppBundle\Exception\SamePostResponseUserEvalUserException;
use AppBundle\Exception\SamePostUserEvalUserException;
use AppBundle\Service\PostResponseService;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Evaluation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Postresponse controller.
 *
 * @Route("postresponse")
 */
class PostResponseController extends Controller
{

    public function __construct(PostResponseService $postResponseService)
    {
        $this->postResponseService = $postResponseService;
    }



    /**
     * @Route("/{id}/up", name="postresponse_up")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function upVoteAction(PostResponse $postResponse){

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $post = $postResponse->getPost();

        //SELECT * FROM evaluation WHERE evaluation.user_id = ? AND evaluation.post_response_id = ?
        $eval = $em->getRepository('AppBundle:Evaluation')->getByUser($user, $postResponse);
        try {

            if ($eval == null) {
                $this->postResponseService->addVote($postResponse, $user, 1);
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }

            // delete the evaluation if second up vote
            if ($eval[0]->getValue() == 1) {
                $this->postResponseService->removeVote($eval);
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }

            // edit the value of the evaluation from -1 to 1
            if ($eval[0]->getValue() == -1) {
                $this->postResponseService->editVote($eval, 1);
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }
        }catch (SamePostResponseUserEvalUserException $exception){
            throw $exception;
        }
        catch (PostClosedException $exception)
        {
            throw $exception;
        }
        catch (SamePostUserEvalUserException $exception)
        {
            throw $exception;
        }


        return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
    }

    /**
     * @Route("/{id}/down", name="postresponse_down")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function downVoteAction(PostResponse $postResponse){


            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $post = $postResponse->getPost();

        //SELECT * FROM evaluation WHERE evaluation.user_id = ? AND evaluation.post_response_id = ?
        $eval = $em->getRepository('AppBundle:Evaluation')->getByUser($user, $postResponse);

            try{
                $event = new Event($eval);
                $this->get('event_dispatcher')->dispatch(Events::prePersist, $event);

                if ($eval == null) {
                    $this->postResponseService->addVote($postResponse, $user, -1);
                    return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                }

                // delete the evaluation if second down vote
                if ($eval[0]->getValue() == -1) {
                    $this->postResponseService->removeVote($eval);
                    return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                }

                // edit the value of the evaluation from 1 to -1
                if ($eval[0]->getValue() == 1) {
                    $this->postResponseService->editVote($eval, -1);
                    return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                }
            } catch (SamePostResponseUserEvalUserException $exception){
                throw $exception;
            }
            catch (PostClosedException $exception)
            {
                throw $exception;
            }

            catch (SamePostUserEvalUserException $exception)
            {
                throw $exception;
            }

            return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
    }

    /**
     * Creates a new postResponse entity.
     *
     * @Route("/new/{id}", name="postresponse_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function newAction(Request $request, Post $post)
    {

            $postResponse = new Postresponse();
            $form = $this->createForm('AppBundle\Form\PostResponseType', $postResponse);
            $form->handleRequest($request);

            $user = $this->getUser();
            $post_user = $post->getUser();
            $postResponse->setUser($user);
            $em = $this->getDoctrine()->getManager();

            //SELECT * FROM post_response WHERE post_response.post_id = ?  AND post_response.user_id = ?
            $postResponseByUser = $em->getRepository('AppBundle:PostResponse')->getByPostandByUser($post, $this->getUser());

            if(($user != $post_user) && ($post->getStatus() != 'closed') && (count($postResponseByUser) < 1)) {
                try{
                    if ($form->isSubmitted() && $form->isValid()) {
                        $postResponse->setPost($post);

                        $em = $this->getDoctrine()->getManager();
                        //INSERT INTO post_response (id, text, created_at, updated_at, user_id, post_id, comment) VALUES (?, ?, ?, ?, ?, ?, ?);
                        $em->persist($postResponse);
                        $em->flush();

                        return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                    }
                }catch (PostClosedException $exception){
                    throw $exception;
                }
                return $this->render('postresponse/new.html.twig', array(
                    'postResponse' => $postResponse,
                    'form' => $form->createView(),
                    'post' => $post
                ));
            }else{
                throw new Exception('Action non autorisée');
            }
    }


    /**
     * Displays a form to edit an existing postResponse entity.
     *
     * @Route("/{id}/edit", name="postresponse_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function editAction(Request $request, PostResponse $postResponse)
    {
        $deleteForm = $this->createDeleteForm($postResponse);
        $editForm = $this->createForm('AppBundle\Form\PostResponseType', $postResponse);
        $editForm->handleRequest($request);
        $post = $postResponse->getPost();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //UPDATE post_response SET ? = ? WHERE post_response.id = ?;
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
        }

        return $this->render('postresponse/edit.html.twig', array(
            'postResponse' => $postResponse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a postResponse entity.
     *
     * @Route("/{id}", name="postresponse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PostResponse $postResponse)
    {
        $form = $this->createDeleteForm($postResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //DELETE FROM postresponse WHERE `postresponse.id = ?
            $em->remove($postResponse);
            $em->flush();
        }

        //return $this->redirectToRoute('post_proofreader_show', array('id' => $postResponse->getPost()->getId()));
    }


    /**
     * Creates a form to delete a postResponse entity.
     *
     * @param PostResponse $postResponse The postResponse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PostResponse $postResponse)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postresponse_delete', array('id' => $postResponse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
