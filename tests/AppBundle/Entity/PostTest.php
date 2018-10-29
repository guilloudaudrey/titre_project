<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\EvaluationPost;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Post;

class PostTest extends TestCase
{

    public function testgetScore(){
        $post = new Post();
        $postEvaluation = new EvaluationPost();
        $postEvaluation->setValue(1);
        $postEvaluation2 = new EvaluationPost();
        $postEvaluation2->setValue(1);
        $post->addPostEvaluation($postEvaluation);
        $post->addPostEvaluation($postEvaluation2);
        $score = $post->getScore();
        $this->assertEquals(2, $score);
    }

    public function testgetScoreWithNegativeValue(){
        $post = new Post();
        $postEvaluation = new EvaluationPost();
        $postEvaluation->setValue(-1);
        $postEvaluation2 = new EvaluationPost();
        $postEvaluation2->setValue(-1);
        $post->addPostEvaluation($postEvaluation);
        $post->addPostEvaluation($postEvaluation2);
        $score = $post->getScore();
        $this->assertEquals(-2, $score);
    }
}