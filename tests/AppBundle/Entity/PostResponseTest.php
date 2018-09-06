<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\Evaluation;
use AppBundle\Entity\PostResponse;
use PHPUnit\Framework\TestCase;

class PostResponseTest extends TestCase
{

    public function testgetScore(){
        $postResponse = new PostResponse();
        $evaluation = new Evaluation();
        $evaluation->setValue(1);
        $evaluation2 = new Evaluation();
        $evaluation2->setValue(1);
        $postResponse->addEvaluation($evaluation);
        $postResponse->addEvaluation($evaluation2);
        $score = $postResponse->getScore();
        $this->assertEquals(2, $score);
    }
}