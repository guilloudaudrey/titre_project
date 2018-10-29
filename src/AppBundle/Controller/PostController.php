<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Post;
use AppBundle\Entity\PostAnswer;
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
 *@Route()
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

        // sql request = SELECT * FROM post
        $postslist = $em->getRepository('AppBundle:Post')->findAllDescByDate();

        // pagination
        $posts  = $this->get('knp_paginator')->paginate(
            $postslist,
            $request->query->get('page', 1)/*page number*/,
            8/*number of elements per page*/
        );

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/post/new", name="post_new")
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
            //INSERT INTO post (id, text, created_at, updated_at, user_id, category_id, comment, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?);
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
     * @Route("/post/{id}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        $postAnswer = new PostAnswer();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\PostAnswerType', $postAnswer);
        $postAnswerByUser = null;

        //SELECT * FROM post_answer LEFT JOIN evaluation  ON post_answer.id = evaluation.post_answer_id WHERE post_answer.post_id = ?
        $postAnswers = $em->getRepository('AppBundle:PostAnswer')->getByPostWithEvaluation($post);
        if($this->getUser()) {
            //SELECT * FROM post_answer WHERE post_answer.post_id = ? AND post_answer.user_id = ?
            $postAnswerByUser = $em->getRepository('AppBundle:PostAnswer')->getByPostandByUser($post, $this->getUser());
        }
        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView(),
            'postAnswers' => $postAnswers,
            'postAnswerByUser' => $postAnswerByUser
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/post/proofreader/{id}", name="post_proofreader_show")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function showProofreaderAction(Post $post, $errormessage = null)
    {
        $deleteForm = $this->createDeleteForm($post);

        $postAnswer = new PostAnswer();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\PostAnswerType', $postAnswer);
        $postAnswerByUser = null;

        //SELECT * FROM post_answer LEFT JOIN evaluation  ON post_answer.id = evaluation.post_answer WHERE post_answer.post_id = ?
        $postAnswers = $em->getRepository('AppBundle:PostAnswer')->getByPostWithEvaluation($post);
        if($this->getUser()) {
            //SELECT * FROM post_answer WHERE post_answer.post_id = ? AND post_answer.user_id = ?
            $postAnswerByUser = $em->getRepository('AppBundle:PostAnswer')->getByPostandByUser($post, $this->getUser());
        }
        return $this->render('post/show_proofreader.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView(),
            'postAnswers' => $postAnswers,
            'errorMessage' => $errormessage,
            'postAnswerByUser' => $postAnswerByUser
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/post/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //UPDATE post SET ? = ? WHERE post.id = ?;
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
     * @Route("/post/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //DELETE FROM post WHERE post.id = 16
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
     * @Route("/post/{id}/up", name="post_up")
     * @Method("GET")
     * @Security("has_role('ROLE_PROOFREADER')")
     */
    public function upVoteAction(Post $post){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        //SELECT * FROM evaluation_post WHERE evaluation_post.user_id = ? AND evaluation_post.post_id = ?
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
