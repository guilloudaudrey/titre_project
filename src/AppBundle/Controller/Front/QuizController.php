<?php

namespace AppBundle\Controller\Front;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller {

    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


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

    private function getTargetUrlFromSession(SessionInterface $session)
    {
        $key = sprintf('_security.%s.target_path', $this->tokenStorage->getToken()->getProviderKey());

        if ($session->has($key)) {
            return $session->get($key);
        }

        return null;
    }


    /**
     * Give the proofreader role to the user.
     *
     * @Route("/proof_reader_role", name="proof_reader_role")
     */
    public function giveProofreaderRole(Request $request){

        $user_id = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("AppBundle:User");
        $user = $userRepository->find($user_id);
        $user->addRole("ROLE_PROOFREADER");
        $em->persist($user);
        $em->flush();

        //pour mettre à jour le nouveau rôle ajouté
        $this->get('fos_user.user_manager')->updateUser($user);
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);



        if($this->getTargetUrlFromSession($request->getSession()) != null) {
            return $this->redirect($this->getTargetUrlFromSession($request->getSession()));

        }else{
            //return $this->redirectToRoute('post_index');
            return $this->redirect($request->headers->get('referer'));
        }



    }

}