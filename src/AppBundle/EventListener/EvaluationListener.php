<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Evaluation;
use AppBundle\Exception\PostClosedException;
use AppBundle\Exception\SamePostResponseUserEvalUserException;
use AppBundle\Exception\SamePostUserEvalUserException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class EvaluationListener
{
    /**
     * @param LifecycleEventArgs $args
     * @throws PostClosedException
     * @throws SamePostResponseUserEvalUserException
     * @throws SamePostUserEvalUserException
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof Evaluation){
            $this->setCreatedAt($entity);

            $postresponse_user = $entity->getPostResponse()->getUser();
            $post_user = $entity->getPostResponse()->getPost()->getUser();

            if ($postresponse_user == $entity->getUser()){
               throw new SamePostResponseUserEvalUserException('vous ne pouvez pas vous auto-évaluer');
            }

            if ($post_user == $entity->getUser()){
                throw new SamePostUserEvalUserException('vous ne pouvez pas évaluer des réponses à votre question');
            }

            if($entity->getPostResponse()->getPost()->getStatus() == 'closed' or $entity->getPostResponse()->getPost()->getStatus() =='noErrors'){
                throw new PostClosedException('vous ne pouvez plus évaluer cette réponse');
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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