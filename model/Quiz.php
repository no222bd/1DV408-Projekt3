<?php

namespace model;

class Quiz {

    private $quizId;
    private $quizName;
    private $questions = array();
    private $creatorId;
    private $isActive;

    public function __construct($quizName, $creatorId, $isActive = false) {
        $this->quizName = $quizName;
        $this->creatorId = $creatorId;
        $this->isActive = $isActive;
    }

    public function getQuizId() {
        return $this->quizId;
    }

    public function getQuizName() {
        return $this->quizName;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function getCreatorId() {
        return $this->creatorId;
    }

    public function setQuizId($quizId) {
        $this->quizId = $quizId;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function addQuestion(\model\Question $question) {
        $this->questions[] = $question;
    }
}
