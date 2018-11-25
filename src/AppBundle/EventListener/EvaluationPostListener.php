<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\EvaluationPost;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EvaluationPostListener
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

        if ($entity instanceof EvaluationPost){
            $this->setCreatedAt($entity);

            // if the post is closed or has no error
            if($entity->getPost()->getStatus() == 'noErrors' or $entity->getPost()->getStatus() == 'closed'){
                throw new PostClosedException('vous ne pouvez plus évaluer cette demande');
            }
        }
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof EvaluationPost) {

            $post = $entity->getPost();

            // if the post has a score of 3 or more
            if ($post->getScore() >= 3) {
                //UPDATE post SET status = 'noErrors' WHERE post.id = ?;
                $post->setStatus('noErrors');
                $this->em->persist($post);
                $this->em->flush();
            }
        }
    }

    public function setCreatedAt(EvaluationPost $evaluation){

        $evaluation->setCreatedAt(new \DateTime());
    }

}