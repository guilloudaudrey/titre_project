<?php

namespace AppBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use AppBundle\Service\ProofreaderRoleService;
use Symfony\Component\HttpFoundation\Response;

class ProofreaderRoleController extends Controller {

    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    private $tokenStorage;
    public $em;

    public function __construct(TokenStorageInterface $tokenStorage, ProofreaderRoleService $quizService, EntityManagerInterface $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->quizService = $quizService;
        $this->em = $em;
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

        $this->quizService->addRoleProofreader($this->getUser());


 /**       if($this->getTargetUrlFromSession($request->getSession()) != null) {
            return $this->redirect($this->getTargetUrlFromSession($request->getSession()));

        }else{**/
            return $this->redirect($request->headers->get('referer'));
        //}
    }
}