<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class QuizService{

    public function __construct(EntityManagerInterface $em, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function addRoleProofreader($current_user){

        $user = $this->em->getRepository("AppBundle:User")->find($current_user->getId());
        $user->addRole("ROLE_PROOFREADER");
        $this->em->persist($user);
        $this->em->flush();

        //pour mettre à jour le nouveau rôle ajouté
        $this->userManager->updateUser($user);
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

    }


}