<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\EvaluationPost;

class PostService{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function postVoteUp($post, $user){
        //create new eval
        $evalpost = new EvaluationPost();

        // set value
        $evalpost->setValue(1);
        $evalpost->setUser($user);
        $evalpost->setPost($post);

        // flush
        $this->em->persist($evalpost);
        $this->em->flush();

    }

    public function removePostVoteUp($score){
        $score_object = $this->em->getRepository('AppBundle:EvaluationPost')->findOneById($score[0]->getId());
        $this->em->remove($score_object);
        $this->em->flush();
    }
}