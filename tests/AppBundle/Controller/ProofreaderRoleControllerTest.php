<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProofreaderRoleControllerTest extends WebTestCase{

    public function testCreateQuizAnswer(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/quizanswer/new');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

    }

    public function testCreateQuizAnswerAuth(){
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/quizanswer/new');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("rôle")')->count()
        );

    }






}