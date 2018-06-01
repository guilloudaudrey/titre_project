<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\PostResponse;

/**
 * PostResponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostResponseRepository extends \Doctrine\ORM\EntityRepository
{

    public function getByPost(Post $post) {
        $query = $this->createQueryBuilder('pr')
            ->where('pr.post = :post')
            ->setParameter('post', $post)
            ->orderBy('pr.createdAt', 'ASC')
            ->getQuery();

        return $query->execute();
    }

    public function getByPostWithEvaluation(Post $post) {
        $query = $this->createQueryBuilder('pr')
        ->select('pr', 'e')
        ->where('pr.post = :post')
        ->setParameter('post', $post)
        ->leftJoin('pr.evaluations', 'e')
        ->orderBy('pr.createdAt', 'ASC')
        ->getQuery();

    return $query->execute();
    }



    // public function sumEvaluations(PostResponse $postResponse) {
    //     $query = $this->createQueryBuilder('pr')
    //         ->innerJoin('pr.evaluations', 'e')
    //         ->where('pr.id = :pr')
    //         ->setParameter('pr', $postResponse)
    //         ->select('pr.id, SUM(e.value) as evaluationValue')
    //         ->getQuery();

    //     return $query->getOneOrNullResult();
    // }
}
