<?php
namespace AppBundle\EventListener;

use AppBundle\AppBundle;
use AppBundle\Entity\PostAnswer;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Exception\PostClosedException;



class PostAnswerListener
{

    /**
     * @param LifecycleEventArgs $args
     * @throws PostClosedException
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof PostAnswer){
            $this->setCreatedAt($entity);

            $post_user = $entity->getPost()->getUser();

            if ($post_user == $entity->getUser()){
                throw new Exception('Vous ne pouvez pas répondre à votre propre message');
            }

            if($entity->getPost()->getStatus() == 'closed' or $entity->getPost()->getStatus() == 'noErrors'){
                throw new PostClosedException('vous ne pouvez plus répondre cette demande');
            }
        }
    }

        /**
     * On pre update entity PostAnswer
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
    
        $this->em = $args->getEntityManager();
                $entity = $args->getEntity();
                if ($entity instanceof PostAnswer){
                    $this->setUpdatedAt($entity);
                } 
    }

    public function setCreatedAt(PostAnswer $postAnswer){
        $postAnswer->setCreatedAt(new \DateTime());
    }

    public function setUpdatedAt(PostAnswer $postAnswer){
        $postAnswer->setUpdatedAt(new \DateTime());
    }



}