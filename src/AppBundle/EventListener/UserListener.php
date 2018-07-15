<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\PostResponse;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\UserBundle\Model\User as BaseUser;

class UserListener{

    /**
     * On pre persist entity User
     *
     * @param PrePersistEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof BaseUser){

            $file = $entity->getAvatarFilename();

            $fileName = $entity->getId().'.'.$file->guessExtension();

            $file->move(
                '%kernel.root_dir%/../web/avatars/',
                $fileName
            );

            $entity->setAvatarFilename($fileName);
        }
    }
}