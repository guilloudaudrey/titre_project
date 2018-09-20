<?php

namespace AppBundle\Event;

use AppBundle\Entity\PostAnswer;
use Symfony\Component\EventDispatcher\Event;

class PostAnswerVoteEvent extends Event{
    const POST_RESPONSE_VOTE = 'event.post_response_vote';

    protected $postResponse;

    public function __construct(PostAnswer $postAnswer){
    $this->postAnswer = $postAnswer;

    }

    public function getPostAnswer(){

        return $this->postAnswer;
    }


}