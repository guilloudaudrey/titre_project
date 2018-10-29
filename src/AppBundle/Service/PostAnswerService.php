<?php

namespace AppBundle\Service;

use AppBundle\Entity\Evaluation;
use Doctrine\ORM\EntityManagerInterface;

class PostAnswerService{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addVote($postAnswer, $user, $value){
        //create new eval
        $evaluation = new Evaluation();
        // set post response
        $evaluation->setPostAnswer($postAnswer);
        // set user
        $evaluation->setUser($user);
        $evaluation->setValue($value);
        // flush
        //INSERT INTO evaluation_post (id, user_id, post_id, value, created_at) VALUES (?, ?, ?, ?, ?);
        $this->em->persist($evaluation);
        $this->em->flush();
    }

    public function removeVote($eval){
        //SELECT * FROM evaluation_post WHERE id = ?
        $eval_object = $this->em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());

        //eval deleted
        //DELETE FROM evaluation_post WHERE evaluation_post.id = ?;
        $this->em->remove($eval_object);
        $this->em->flush();
    }

    public function editVote($eval, $value){
        $eval_object = $this->em->getRepository('AppBundle:Evaluation')->findOneById($eval[0]->getId());
        $eval_object->setValue($value);

        //INSERT INTO evaluation_post (id, user_id, post_id, value, created_at) VALUES (?, ?, ?, ?, ?);
        $this->em->persist($eval_object);
        $this->em->flush();

    }
}