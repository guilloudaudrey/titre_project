<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="user", cascade={"remove"})
     *
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PostAnswer", mappedBy="user", cascade={"remove"})
     */
    private $post_answers;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluation", mappedBy="user", cascade={"remove"})
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EvaluationPost", mappedBy="user", cascade={"remove"})
     */
    private $post_evaluations;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
    * @ORM\PrePersist
    */
    public function setUpdatedTimestamp(){
        $this->createdAt = new \DateTime();
    }


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
     * Add postAnswer
     *
     * @param \AppBundle\Entity\PostAnswer $postAnswer
     *
     * @return User
     */
    public function addPostAnswer(\AppBundle\Entity\PostAnswer $postAnswer)
    {
        $this->post_answers[] = $postAnswer;

        return $this;
    }

    /**
     * Remove postAnswer
     *
     * @param \AppBundle\Entity\PostAnswer $postAnswer
     */
    public function removePostAnswer(\AppBundle\Entity\PostAnswer $postAnswer)
    {
        $this->post_answers->removeElement($postAnswer);
    }

    /**
     * Get postAnswers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostAnswers()
    {
        return $this->post_answers;
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

    public function getFirstLetterOfUserName(){
        return $this->username[0];
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }



    /**
     * Add postEvaluation
     *
     * @param \AppBundle\Entity\EvaluationPost $postEvaluation
     *
     * @return User
     */
    public function addPostEvaluation(\AppBundle\Entity\EvaluationPost $postEvaluation)
    {
        $this->post_evaluations[] = $postEvaluation;

        return $this;
    }

    /**
     * Remove postEvaluation
     *
     * @param \AppBundle\Entity\EvaluationPost $postEvaluation
     */
    public function removePostEvaluation(\AppBundle\Entity\EvaluationPost $postEvaluation)
    {
        $this->post_evaluations->removeElement($postEvaluation);
    }

    /**
     * Get postEvaluations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostEvaluations()
    {
        return $this->post_evaluations;
    }
}
