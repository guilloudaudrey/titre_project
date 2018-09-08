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
        //INSERT INTO evaluation_post (id, user_id, post_id, value, created_at) VALUES (:id, :user_id, :post_id, :value, :created_at);
        $this->em->persist($evalpost);
        $this->em->flush();
    }

    public function removePostVoteUp($score){
        //SELECT * FROM evaluation_post WHERE id = ?
        $score_object = $this->em->getRepository('AppBundle:EvaluationPost')->findOneById($score[0]->getId());
        //DELETE FROM evaluation_post WHERE evaluation_post.id = 1
        $this->em->remove($score_object);
        $this->em->flush();
    }
}