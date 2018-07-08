<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizAnswer
 *
 * @ORM\Table(name="quiz_answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizAnswerRepository")
 */
class QuizAnswer
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="test_passed", type="boolean")
     */
    private $testPassed;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="quizAnswers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\QuizQuestion", inversedBy="quizAnswers")
     **/
    private $quizQuestions;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return QuizAnswer
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
     * Set testPassed
     *
     * @param boolean $testPassed
     *
     * @return QuizAnswer
     */
    public function setTestPassed($testPassed)
    {
        $this->testPassed = $testPassed;

        return $this;
    }

    /**
     * Get testPassed
     *
     * @return bool
     */
    public function getTestPassed()
    {
        return $this->testPassed;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return QuizAnswer
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
     * Constructor
     */
    public function __construct()
    {
        $this->quizQuestions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add quizQuestion
     *
     * @param \AppBundle\Entity\QuizQuestions $quizQuestion
     *
     * @return QuizAnswer
     */
    public function addQuizQuestion(\AppBundle\Entity\QuizQuestions $quizQuestion)
    {
        $this->quizQuestions[] = $quizQuestion;

        return $this;
    }

    /**
     * Remove quizQuestion
     *
     * @param \AppBundle\Entity\QuizQuestions $quizQuestion
     */
    public function removeQuizQuestion(\AppBundle\Entity\QuizQuestions $quizQuestion)
    {
        $this->quizQuestions->removeElement($quizQuestion);
    }

    /**
     * Get quizQuestions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizQuestions()
    {
        return $this->quizQuestions;
    }
}
