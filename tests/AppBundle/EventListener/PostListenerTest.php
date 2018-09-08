<?php

namespace tests\AppBundle\EventListener;

use AppBundle\EventListener\PostListener;
use PHPUnit\Framework\TestCase;

class PostListenerTest extends TestCase
{


    public function testsetCreatedAt(){
        $postlistener = $this->createMock(PostListener::class);
        $this->assertEquals(new \DateTime(),  $postlistener->set);

    }
}