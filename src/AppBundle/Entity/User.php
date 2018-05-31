<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PostResponse", mappedBy="user")
     */
    private $post_responses;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluation", mappedBy="user")
     */
    private $evaluations;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add post
     *
     * @param \ApiBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(\ApiBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \ApiBundle\Entity\Post $post
     */
    public function removePost(\ApiBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add evaluation
     *
     * @param \AppBundle\Entity\Evaluation $evaluation
     *
     * @return User
     */
    public function addEvaluation(\AppBundle\Entity\Evaluation $evaluation)
    {
        $this->evaluations[] = $evaluation;

        return $this;
    }

    /**
     * Remove evaluation
     *
     * @param \AppBundle\Entity\Evaluation $evaluation
     */
    public function removeEvaluation(\AppBundle\Entity\Evaluation $evaluation)
    {
        $this->evaluations->removeElement($evaluation);
    }

    /**
     * Get evaluations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluations()
    {
        return $this->evaluations;
    }

    /**
     * Add postResponse
     *
     * @param \AppBundle\Entity\PostResponse $postResponse
     *
     * @return User
     */
    public function addPostResponse(\AppBundle\Entity\PostResponse $postResponse)
    {
        $this->post_responses[] = $postResponse;

        return $this;
    }

    /**
     * Remove postResponse
     *
     * @param \AppBundle\Entity\PostResponse $postResponse
     */
    public function removePostResponse(\AppBundle\Entity\PostResponse $postResponse)
    {
        $this->post_responses->removeElement($postResponse);
    }

    /**
     * Get postResponses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostResponses()
    {
        return $this->post_responses;
    }
}
