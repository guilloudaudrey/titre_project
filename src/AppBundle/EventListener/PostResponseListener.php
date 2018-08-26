<?php
namespace AppBundle\EventListener;

use AppBundle\AppBundle;
use AppBundle\Entity\PostResponse;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Exception\PostClosedException;



class PostResponseListener
{

    /**
     * On pre persist entity PostResponse
     *
     * @param PrePersistEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof PostResponse){
            $this->setCreatedAt($entity);

            $post_user = $entity->getPost()->getUser();

            if ($post_user == $entity->getUser()){
                throw new Exception('Vous ne pouvez pas répondre à votre propre message');
            }

            if($entity->getPost()->getStatus() == 'closed'){
                throw new PostClosedException('vous ne pouvez plus répondre cette demande');
            }
        }
    }

        /**
     * On pre update entity PostResponse
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
    
        $this->em = $args->getEntityManager();
        
                $entity = $args->getEntity();

                if ($entity instanceof PostResponse){

                    $this->setUpdatedAt($entity);
                } 
    }

    public function setCreatedAt(PostResponse $postResponse){

        $postResponse->setCreatedAt(new \DateTime());
    }

    public function setUpdatedAt(PostResponse $postResponse){

        $postResponse->setUpdatedAt(new \DateTime());
    }



}