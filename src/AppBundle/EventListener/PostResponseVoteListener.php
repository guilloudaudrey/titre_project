<?php

namespace AppBundle\EventListener;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\Event;

class PostResponseVoteListener{

    public function checkIfUserIsTheSameAsPostResponseUser(Event $event){

        $user = $this->getUser();

        $post_response_user = $event->getPostResponse()->getUser();

        if ($user == $post_response_user){
            throw new Exception("Vous ne pouvez pas évaluer votre réponse");
        } else{
            return;
        }

    }
}