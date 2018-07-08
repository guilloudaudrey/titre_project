<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizQuestion
 *
 * @ORM\Table(name="quiz_question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizQuestionRepository")
 */
class QuizQuestion
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
     * @ORM\Column(name="statement", type="string", length=255, unique=true)
     */
    private $statement;

    /**
     * @var string
     *
     * @ORM\Column(name="solution", type="string", length=255)
     */
    private $solution;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz", inversedBy="quizQuestions")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;


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
     * Set statement
     *
     * @param string $statement
     *
     * @return QuizQuestion
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;

        return $this;
    }

    /**
     * Get statement
     *
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Set solution
     *
     * @param string $solution
     *
     * @return QuizQuestion
     */
    public function setSolution($solution)
    {
        $this->solution = $solution;

        return $this;
    }

    /**
     * Get solution
     *
     * @return string
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz $quiz
     *
     * @return QuizQuestion
     */
    public function setQuiz(\AppBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \AppBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizAnswers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add quizAnswer
     *
     * @param \AppBundle\Entity\QuizAnswer $quizAnswer
     *
     * @return QuizQuestion
     */
    public function addQuizAnswer(\AppBundle\Entity\QuizAnswer $quizAnswer)
    {
        $this->quizAnswers[] = $quizAnswer;

        return $this;
    }

    /**
     * Remove quizAnswer
     *
     * @param \AppBundle\Entity\QuizAnswer $quizAnswer
     */
    public function removeQuizAnswer(\AppBundle\Entity\QuizAnswer $quizAnswer)
    {
        $this->quizAnswers->removeElement($quizAnswer);
    }

    /**
     * Get quizAnswers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizAnswers()
    {
        return $this->quizAnswers;
    }
}
