<?php

namespace AppBundle\Service;

use AppBundle\Entity\Evaluation;
use Doctrine\ORM\EntityManagerInterface;

class PostResponseService{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addVote($postResponse, $user, $value){
        //create new eval
        $evaluation = new Evaluation();
        // set post response
        $evaluation->setPostResponse($postResponse);
        // set user
        $evaluation->setUser($user);
        $evaluation->setValue($value);
        // flush
        $this->em->persist($evaluation);
        $this->em->flush();
    }

    public function removeVote($eval){
        $eval_object = $this->em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
        //suppression de l'éval
        $this->em->remove($eval_object);
        $this->em->flush();
    }

    public function editVote($eval, $value){
        $eval_object = $this->em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
        $eval_object->setValue($value);
        $this->em->persist($eval_object);
        $this->em->flush();

    }
}