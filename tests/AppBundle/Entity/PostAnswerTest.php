<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\Evaluation;
use AppBundle\Entity\PostAnswer;
use PHPUnit\Framework\TestCase;

class PostAnswerTest extends TestCase
{


    public function testgetScore(){
        $postAnswer = new PostAnswer();
        $evaluation = new Evaluation();
        $evaluation->setValue(1);
        $evaluation2 = new Evaluation();
        $evaluation2->setValue(1);
        $postAnswer->addEvaluation($evaluation);
        $postAnswer->addEvaluation($evaluation2);
        $score = $postAnswer->getScore();
        $this->assertEquals(2, $score);
    }
}