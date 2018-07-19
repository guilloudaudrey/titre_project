<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\PostResponse;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\UserBundle\Model\User as BaseUser;

class UserListener{

    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
    /**
     * On post persist entity User
     * @param PostPersistEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof BaseUser){

            $file = $entity->getAvatarFilename();

            $fileName = $entity->getId().'.'.$file->guessExtension();

            $file->move(
                $this->targetDirectory,
                $fileName
            );

            $entity->setAvatarFilename($fileName);
            $this->em->persist($entity);
            $this->em->flush();
        }
    }
}