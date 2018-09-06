<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\User;
use AppBundle\Entity\Post;
use AppBundle\Entity\PostResponse;
use AppBundle\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\EvaluationPost;

/**
 * Post controller.
 *
 * @Route("post")
 */
class PostController extends Controller
{
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }


    /**
     * Lists all post entities.
     *
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $postslist = $em->getRepository('AppBundle:Post')->findAll();


        $posts  = $this->get('knp_paginator')->paginate(
            $postslist,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            8/*nbre d'éléments par page*/
        );

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {

        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        $user = $this->getUser();
        $post->setUser($user);
        $post->setStatus('active');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/{id}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        $postResponse = new Postresponse();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\PostResponseType', $postResponse);
        $postResponseByUser = null;
        
        $postResponses = $em->getRepository('AppBundle:PostResponse')->getByPostWithEvaluation($post);
        if($this->getUser()) {
            $postResponseByUser = $em->getRepository('AppBundle:PostResponse')->getByPostandByUser($post, $this->getUser());
        }
        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView(),
            'postResponses' => $postResponses,
            'postResponseByUser' => $postResponseByUser
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/proofreader/{id}", name="post_proofreader_show")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function showProofreaderAction(Post $post, $errormessage = null)
    {
        $deleteForm = $this->createDeleteForm($post);

        $postResponse = new Postresponse();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\PostResponseType', $postResponse);
        $postResponseByUser = null;

        $postResponses = $em->getRepository('AppBundle:PostResponse')->getByPostWithEvaluation($post);
        if($this->getUser()) {
            $postResponseByUser = $em->getRepository('AppBundle:PostResponse')->getByPostandByUser($post, $this->getUser());
        }
        return $this->render('post/show_proofreader.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView(),
            'postResponses' => $postResponses,
            'errorMessage' => $errormessage,
            'postResponseByUser' => $postResponseByUser
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/up", name="post_up")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function upVoteAction(Post $post){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $score = $eval = $em->getRepository('AppBundle:EvaluationPost')->getByUser($user, $post);
        try {

            if ($score == null) {
                $this->postService->postVoteUp($post, $user);
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            } else {
                $this->postService->removePostVoteUp($score);
                return $this->redirectToRoute('post_proofreader_show', array('id' => $post->getId()));
            }

            $this->postService->postVoteUp($score, $post, $user);

        } catch (PostClosedException $exception)
        {
            throw $exception;
        }

    }


}
