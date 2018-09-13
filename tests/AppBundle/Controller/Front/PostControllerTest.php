<?php

namespace Tests\AppBundle\Controller\Front;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testIndexContains()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testShowPost()
    {

        $client = static::createClient();
        $client->request('GET', '/post/7');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testNewPost()
    {
        $client = static::createClient();
        $client->request('GET', '/post/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditPost()
    {
        $client = static::createClient();
        $client->request('GET', '/post/7/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }


    public function testClickingPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testClickingPostContains()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Consulter")')->count()
        );
    }

    public function testClickingPostConsult()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $link2 = $crawler->filter('.post_consult')->first()->link();
        $client->click($link2);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testClickingPostContrib()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $link2 = $crawler->filter('.post_contrib')->first()->link();
        $client->click($link2);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

}