<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;


    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EvaluationPost", mappedBy="post", cascade={"remove"})
     */
    private $post_evaluations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PostAnswer", mappedBy="post", cascade={"remove"})
     */
    private $post_answers;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Post
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Post
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->post_answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add postAnswer
     *
     * @param \AppBundle\Entity\PostAnswer $postAnswer
     *
     * @return Post
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Post
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function __toString()
    {
        return $this->text;
    }

    /**
     * Add postEvaluation
     *
     * @param \AppBundle\Entity\EvaluationPost $postEvaluation
     *
     * @return Post
     */
    public function addPostEvaluation(\AppBundle\Entity\EvaluationPost $postEvaluation)
    {
        $this->post_evaluations[] = $postEvaluation;

        return $this;
    }

    /**
     * Remove postEvaluation
     *
     * @param \AppBundle\Entity\EvaluatioPost $postEvaluation
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

    /**
     * @return int
     */

    public function getScore() {
        $score = 0;

        foreach($this->post_evaluations as $e) {
            $score += intval($e->getValue());
        }

        return $score;
    }
}
