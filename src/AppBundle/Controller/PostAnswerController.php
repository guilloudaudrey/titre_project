<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostAnswer;
use AppBundle\Event\PostAnswerVoteEvent;
use AppBundle\Exception\PostClosedException;
use AppBundle\Exception\SamePostAnswereUserEvalUserException;
use AppBundle\Exception\SamePostUserEvalUserException;
use AppBundle\Service\PostAnswerService;
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
 * Postanswer controller.
 *
 * @Route("postanswer")
 */
class PostAnswerController extends Controller
{

    protected $postAnswerServiceService;

    public function __construct(PostAnswerService $postAnswerService)
    {
        $this->postAnswerService = $postAnswerService;
    }


    /**
     * @Route("/{id}/up", name="postanswer_up")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function upVoteAction(PostAnswer $postAnswer){

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $post = $postAnswer->getPost();

        //SELECT * FROM evaluation WHERE evaluation.user_id = ? AND evaluation.post_answer = ?
        $eval = $em->getRepository('AppBundle:Evaluation')->getByUser($user, $postAnswer);
        try {

            if ($eval == null) {
                $this->postAnswerService->addVote($postAnswer, $user, 1);
                //return new Response('success');
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }

            // delete the evaluation if second up vote
            if ($eval[0]->getValue() == 1) {
                $this->postAnswerService->removeVote($eval);
                //return new Response('success');
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }

            // edit the value of the evaluation from -1 to 1
            if ($eval[0]->getValue() == -1) {
                $this->postAnswerService->editVote($eval, 1);
                //return new Response('success');
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }
        }catch (SamePostAnswerUserEvalUserException $exception){
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

        //return new Response('error');
        return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
    }

    /**
     * @Route("/{id}/down", name="postanswer_down")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function downVoteAction(PostAnswer $postAnswer){


            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $post = $postAnswer->getPost();

        //SELECT * FROM evaluation WHERE evaluation.user_id = ? AND evaluation.post_answer = ?
        $eval = $em->getRepository('AppBundle:Evaluation')->getByUser($user, $postAnswer);

            try{
                $event = new Event($eval);
                $this->get('event_dispatcher')->dispatch(Events::prePersist, $event);

                if ($eval == null) {
                    $this->postAnswerService->addVote($postAnswer, $user, -1);
                    return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                }

                // delete the evaluation if second down vote
                if ($eval[0]->getValue() == -1) {
                    $this->postAnswerService->removeVote($eval);
                    return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                }

                // edit the value of the evaluation from 1 to -1
                if ($eval[0]->getValue() == 1) {
                    $this->postAnswerService->editVote($eval, -1);
                    return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                }
            } catch (SamePostAnswerUserEvalUserException $exception){
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
     * Creates a new postAnswer entity.
     *
     * @Route("/new/{id}", name="postanswer_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function newAction(Request $request, Post $post)
    {

            $postAnswer = new PostAnswer();
            $form = $this->createForm('AppBundle\Form\PostAnswerType', $postAnswer);
            $form->handleRequest($request);

            $user = $this->getUser();
            $post_user = $post->getUser();
            $postAnswer->setUser($user);
            $em = $this->getDoctrine()->getManager();

            //SELECT * FROM post_answer WHERE post_answer.post_id = ?  AND post_answer.user_id = ?
            $postAnswerByUser = $em->getRepository('AppBundle:PostAnswer')->getByPostandByUser($post, $this->getUser());

            if(($user != $post_user) && ($post->getStatus() != 'closed') && (count($postAnswerByUser) < 1)) {
                try{
                    if ($form->isSubmitted() && $form->isValid()) {
                        $postAnswer->setPost($post);

                        $em = $this->getDoctrine()->getManager();
                        //INSERT INTO post_answer (id, text, created_at, updated_at, user_id, post_id, comment) VALUES (?, ?, ?, ?, ?, ?, ?);
                        $em->persist($postAnswer);
                        $em->flush();

                        return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
                    }
                }catch (PostClosedException $exception){
                    throw $exception;
                }
                return $this->render('postanswer/new.html.twig', array(
                    'post_answer' => $postAnswer,
                    'form' => $form->createView(),
                    'post' => $post
                ));
            }else{
                throw new Exception('Action non autorisée');
            }
    }


    /**
     * Displays a form to edit an existing postAnswer entity.
     *
     * @Route("/{id}/edit", name="postanswer_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function editAction(Request $request, PostAnswer $postAnswer)
    {
        $deleteForm = $this->createDeleteForm($postAnswer);
        $editForm = $this->createForm('AppBundle\Form\PostAnswerType', $postAnswer);
        $editForm->handleRequest($request);
        $post = $postAnswer->getPost();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //UPDATE post_answer SET ? = ? WHERE post_answer.id = ?;
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
        }

        return $this->render('postanswer/edit.html.twig', array(
            'postAnswer' => $postAnswer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a postAnswer entity.
     *
     * @Route("/{id}", name="postanswer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PostAnswer $postAnswer)
    {
        $form = $this->createDeleteForm($postAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //DELETE FROM post_answer WHERE `post_answer.id = ?
            $em->remove($postAnswer);
            $em->flush();
        }

    }


    /**
     * Creates a form to delete a postAnswer entity.
     *
     * @param PostAnswer $postAnswer The postAnswer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PostAnswer $postAnswer)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postanswer_delete', array('id' => $postAnswer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
