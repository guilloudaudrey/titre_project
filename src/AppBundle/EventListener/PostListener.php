<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Post;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class PostListener
{

    /**
     * On pre persist entity post
     *
     * @param PrePersistEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();
        
        if ($entity instanceof Post){
            $this->setCreatedAt($entity);
        }
    }

        /**
     * On pre update entity post
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof Post){
            $this->setUpdatedAt($entity);
        }
    }

    public function setCreatedAt(Post $post){

        $post->setCreatedAt(new \DateTime());
    }

    public function setUpdatedAt(Post $post){
        
        $post->setUpdatedAt(new \DateTime());
    }



}