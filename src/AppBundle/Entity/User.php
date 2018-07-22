<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
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
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_filename", type="string", length=255, nullable=true)
     */
    protected $avatar_filename;

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
    }

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(\AppBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AppBundle\Entity\Post $post
     */
    public function removePost(\AppBundle\Entity\Post $post)
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



    /**
     * Set avatarFilename
     *
     * @param string $avatarFilename
     *
     * @return User
     */
    public function setAvatarFilename($avatarFilename)
    {
        $this->avatar_filename = $avatarFilename;

        return $this;
    }

    /**
     * Get avatarFilename
     *
     * @return string
     */
    public function getAvatarFilename()
    {
        return $this->avatar_filename;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
}
