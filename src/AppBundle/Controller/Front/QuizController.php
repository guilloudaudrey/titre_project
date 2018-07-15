<?php

namespace AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class QuizController extends Controller {


    /**
     * Creates a new quiz.
     *
     * @Route("/quizanswer/new", name="quiz_answer_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function createQuizAnswer(){



        return $this->render('quizanswer/new.html.twig');
    }

    /**
     * Give the proofreader role to the user.
     *
     * @Route("/quizanswer/proof_reader_role", name="proof_reader_role")
     */
    public function giveProofreaderRole(){

        $user_id = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("AppBundle:User");
        $user = $userRepository->find($user_id);
        $user->addRole("ROLE_PROOFREADER");
        $em->persist($user);
        $em->flush();

        return $this->render('quizanswer/bravoproofreader.html.twig');

    }

}