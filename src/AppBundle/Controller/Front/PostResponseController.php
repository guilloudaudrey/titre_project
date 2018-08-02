<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostResponse;
use AppBundle\Event\PostResponseVoteEvent;
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
    /**
     * Lists all postResponse entities.
     *
     * @Route("/", name="postresponse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $postResponses = $em->getRepository('AppBundle:PostResponse')->findAll();

        return $this->render('postresponse/index.html.twig', array(
            'postResponses' => $postResponses,
        ));
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

        $eval = $em->getRepository('AppBundle:Evaluation')->getByUser($user, $postResponse);


        try {

            if ($eval == null) {
                //create new eval
                $evaluation = new Evaluation();
                // set post response
                $evaluation->setPostResponse($postResponse);
                // set user
                $evaluation->setUser($user);
                $evaluation->setValue(1);
                // flush
                $em->persist($evaluation);
                $em->flush();

                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }

            // delete the evaluation if second up vote
            if ($eval[0]->getValue() == 1) {
                $eval_object = $em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
                //suppression de l'éval
                $em->remove($eval_object);
                $em->flush();

                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }

            // edit the value of the evaluation from -1 to 1
            if ($eval[0]->getValue() == -1) {
                $eval_object = $em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
                $eval_object->setValue(1);
                $em->persist($eval_object);
                $em->flush();

                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }
        }catch (Exception $exception){

        }

        return $this->redirectToRoute('post_show', array('id' => $post->getId()));
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

            $eval = $em->getRepository('AppBundle:Evaluation')->getByUser($user, $postResponse);

            try{
                $event = new Event($eval);
                $this->get('event_dispatcher')->dispatch(Events::prePersist, $event);

                if ($eval == null) {
                //create new eval
                $evaluation = new Evaluation();

                // set post response
                $evaluation->setPostResponse($postResponse);

                // set user
                $evaluation->setUser($user);
                $evaluation->setValue(-1);

                // flush
                $em->persist($evaluation);
                $em->flush();

                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }

            // delete the evaluation if second down vote
            if ($eval[0]->getValue() == -1) {
                $eval_object = $em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
                //suppression de l'éval
                $em->remove($eval_object);
                $em->flush();

                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }

            // edit the value of the evaluation from 1 to -1
            if ($eval[0]->getValue() == 1) {
                $eval_object = $em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
                $eval_object->setValue(-1);
                $em->persist($eval_object);
                $em->flush();

                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }
            } catch (Exception $exception){

                $response = $this->forward('AppBundle\Controller\Front\PostController::showAction', array(
                'post' => $post,
                'errormessage' => "Vous ne pouvez pas vous auto-évaluer !"
                ));

                return $response;


            }

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));

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

            if($user != $post_user) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $postResponse->setPost($post);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($postResponse);
                    $em->flush();

                    return $this->redirectToRoute('post_show', array('id' => $post->getId()));
                }

                return $this->render('postresponse/new.html.twig', array(
                    'postResponse' => $postResponse,
                    'form' => $form->createView(),
                    'post' => $post
                ));
            }else{
                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }

    }

    /**
     * Finds and displays a postResponse entity.
     *
     * @Route("/{id}", name="postresponse_show")
     * @Method("GET")
     */
    public function showResponseAction(PostResponse $postResponse, Post $post)
    {
        $em = $this->getDoctrine()->getManager();

                $postResponses = $em->getRepository('AppBundle:PostResponse')->findAll();

                return $this->render('postresponse/index.html.twig', array(
                    'postResponses' => $postResponses,
                ));
    }

    /**
     * Finds and displays a postResponse entity.
     *
     * @Route("/{id}", name="postresponse_show")
     * @Method("GET")
     */
    public function showAction(PostResponse $postResponse)
    {
        $deleteForm = $this->createDeleteForm($postResponse);

        return $this->render('postresponse/show.html.twig', array(
            'postResponse' => $postResponse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing postResponse entity.
     *
     * @Route("/{id}/edit", name="postresponse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PostResponse $postResponse)
    {
        $deleteForm = $this->createDeleteForm($postResponse);
        $editForm = $this->createForm('AppBundle\Form\PostResponseType', $postResponse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('postresponse_edit', array('id' => $postResponse->getId()));
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
            $em->remove($postResponse);
            $em->flush();
        }

        return $this->redirectToRoute('postresponse_index');
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
