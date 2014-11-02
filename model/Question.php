<?php

namespace model;

class Question {

    private $questionId;
    private $question;
    private $answers = array();
    private $mediaPath;

    public function __construct($question, array $answers) {

        $this->question = $question;

        foreach ($answers as $answer) {
            $this->answers[] = $answer;
        }
    }

    public function getQuestionId() {
        return $this->questionId;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function getCorrectAnswer() {
        return $this->answers[0];
    }

    public function getMediaPath() {
        return $this->mediaPath;
    }

    public function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }

    public function setMediaPath($mediaPath) {
        $this->mediaPath = $mediaPath;
    }

    public function isCorrect($answer) {
        return strtolower($answer) == strtolower($this->answers[0]);
    }
}
