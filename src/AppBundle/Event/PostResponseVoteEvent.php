<?php

namespace AppBundle\Event;

use AppBundle\Entity\PostResponse;
use Symfony\Component\EventDispatcher\Event;

class PostResponseVoteEvent extends Event{
    const POST_RESPONSE_VOTE = 'event.post_response_vote';

    protected $postResponse;

    public function __construct(PostResponse $postResponse){
    $this->postResponse = $postResponse;

    }

    public function getPostResponse(){

        return $this->postResponse;
    }


}