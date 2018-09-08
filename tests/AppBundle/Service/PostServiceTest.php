<?php

namespace tests\AppBundle\Service;

use AppBundle\Entity\EvaluationPost;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class PostServiceTest extends TestCase{

    public function testpostVoteUp(){
        $user = $this->createMock(User::class);
        $post = $this->createMock(Post::class);

        $evalpost = new EvaluationPost();
        $evalpost->setValue(1);
        $evalpost->setUser($user);
        $evalpost->setPost($post);

        $this->assertEquals(1,  $evalpost->getValue());
    }

    public function testpostVoteUpInstance(){
        $user = $this->createMock(User::class);
        $post = $this->createMock(Post::class);

        $evalpost = new EvaluationPost();
        $evalpost->setValue(1);
        $evalpost->setUser($user);
        $evalpost->setPost($post);

        $this->assertInstanceOf(EvaluationPost::class, $evalpost);
    }

}
