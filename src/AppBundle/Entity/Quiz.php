<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizRepository")
 */
class Quiz
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\QuizQuestion", mappedBy="quiz")
     */
    private $quizQuestions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizQuestions = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add quizQuestion
     *
     * @param \AppBundle\Entity\QuizQuestion $quizQuestion
     *
     * @return Quiz
     */
    public function addQuizQuestion(\AppBundle\Entity\QuizQuestion $quizQuestion)
    {
        $this->quizQuestions[] = $quizQuestion;

        return $this;
    }

    /**
     * Remove quizQuestion
     *
     * @param \AppBundle\Entity\QuizQuestion $quizQuestion
     */
    public function removeQuizQuestion(\AppBundle\Entity\QuizQuestion $quizQuestion)
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
