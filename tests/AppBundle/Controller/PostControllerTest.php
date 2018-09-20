<?php

namespace Tests\AppBundle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Une hésitation")')->count()
        );
    }


    public function testShowPost()
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/post/49');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Commentaire")')->count()
        );
    }

    public function testNewPostWithoutAuth()
    {
        $client = static::createClient();
        $client->request('GET', '/post/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testNewPostAuth()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/post/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nouvelle demande")')->count()
        );
    }


    public function testPostForm()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/post/new');
        $this->assertEquals('AppBundle\Controller\Front\PostController::newAction', $client->getRequest()->attributes->get('_controller'));
        $form = $crawler->selectButton('Valider')->form(array(
            'appbundle_post[text]'      => 'test test test',
            'appbundle_post[comment]'   => 'test test test',
            'appbundle_post[category]'  => 1,
        ));
        $client->submit($form);
        $this->assertEquals('AppBundle\Controller\Front\PostController::newAction', $client->getRequest()->attributes->get('_controller'));

        $client->followRedirect();
        $this->assertEquals('AppBundle\Controller\Front\PostController::showAction', $client->getRequest()->attributes->get('_controller'));
    }

    public function testPostFormInvalid()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/post/new');
        $this->assertEquals('AppBundle\Controller\Front\PostController::newAction', $client->getRequest()->attributes->get('_controller'));
        $form = $crawler->selectButton('Valider')->form();
        $client->submit($form);
        $this->assertSame(500, $client->getResponse()->getStatusCode());
    }



    public function testEditWithoutAuth()
    {
        $client = static::createClient();
        $client->request('GET', '/post/49/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditAuth()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/post/49/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Modification de la demande")')->count()
        );
    }


    public function testEditForm()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/post/49/edit');
        $this->assertEquals('AppBundle\Controller\Front\PostController::editAction', $client->getRequest()->attributes->get('_controller'));
        $form = $crawler->selectButton('Modifier')->form(array(
            'appbundle_post[text]'      => 'test edit',
        ));
        $client->submit($form);
        $this->assertEquals('AppBundle\Controller\Front\PostController::editAction', $client->getRequest()->attributes->get('_controller'));

        $client->followRedirect();
        $this->assertEquals('AppBundle\Controller\Front\PostController::showProofreaderAction', $client->getRequest()->attributes->get('_controller'));
         }


    public function testClickingPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Consulter")')->count()
        );
    }


    public function testClickingPostConsult()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_consult')->first()->link();
        $client->click($link);
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


    public function testClickingPostContribAuthProofreader()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.post_link')->first()->link();
        $client->click($link);
        $link2 = $crawler->filter('.post_contrib')->first()->link();
        $client->click($link2);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testClickingProfile()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.profile_link')->first()->link();
        $client->click($link);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }



    public function testClickingAdmin()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW'   => 'aaa',
        ));
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.admin_link')->first()->link();
        $client->click($link);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }






}