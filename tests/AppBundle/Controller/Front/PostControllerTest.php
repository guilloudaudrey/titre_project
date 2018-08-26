<?php

namespace Tests\AppBundle\Controller\Front;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase {

    public function testIndexAction(){
        $client = static::createClient();
        $client->request('GET', '/post/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    public function testShowPost(){

        $client = static::createClient();
        $client->request('GET', '/post/7');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testClickingPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/post/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostingWithoutBeingLogin(){
        $post = new Post();
        $this->expectException('LogicException');
    }

}