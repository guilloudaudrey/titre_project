<?php

namespace Tests\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase{

    public function testCreateQuizAnswer(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/quizanswer/new');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

    }

    public function testCreateQuizAnswerAuth(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/quizanswer/new');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

    }






}