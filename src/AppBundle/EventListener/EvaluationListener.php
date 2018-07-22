<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Evaluation;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Config\Definition\Exception\Exception;
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

            $postresponse_user = $entity->getPostResponse()->getUser();

            if ($postresponse_user == $entity->getUser()){
               throw new Exception('');
            }
        }


    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof Evaluation){
            $this->setCreatedAt($entity);

            $postresponse = $entity->getPostResponse();
            $post = $postresponse->getPost();
            $evaluations = $postresponse->getEvaluations();

            if ($postresponse->getScore() >= 3){

                $post->setStatus('closed');
$this->em->persist($post);
$this->em->flush();

            }
        }


    }

    public function setCreatedAt(Evaluation $evaluation){

        $evaluation->setCreatedAt(new \DateTime());
    }

}