<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\PostResponse;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



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

    public function setUpdatedAt(Post $postResponse){

        $postResponse->setUpdatedAt(new \DateTime());
    }



}