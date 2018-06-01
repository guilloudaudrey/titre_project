<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Evaluation;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EvaluationListener
{

    /**
     * On pre persist entity evaluation
     *
     * @param PrePersistEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        
                $entity = $args->getEntity();
        
                if ($entity instanceof Evaluation){
                $this->setCreatedAt($entity);
                }
    }

    public function setCreatedAt(Evaluation $evaluation){

        $evaluation->setCreatedAt(new \DateTime());
    }





}