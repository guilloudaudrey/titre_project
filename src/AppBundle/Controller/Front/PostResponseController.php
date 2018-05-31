<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
     * Creates a new postResponse entity.
     *
     * @Route("/new/{id}", name="postresponse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Post $post)
    {
        $postResponse = new Postresponse();
        $form = $this->createForm('AppBundle\Form\PostResponseType', $postResponse);
        $form->handleRequest($request);

        $user = $this->getUser();
        $postResponse->setUser($user);

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
