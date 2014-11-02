<?php

namespace view;

class Question {

    public function getHTML(\model\Question $question) {

        $html = '<h2>' . $question->getQuestion() . '</h2>';

        if ($question->getMediaPath() !== NULL) {
            $html .= '<img src="' . $question->getMediaPath() . '" />';
        }

        $html .= '<form method="POST">';
       
        $answers = $question->getAnswers();
        shuffle($answers);

        // Skicka questionId i dolt fält
        $html .= '<input type="hidden" value="' . $question->getQuestionId() . '" name="questionId" />';

        foreach ($answers as $answer) {
            $html .= '<label>' .
                        '<input type="radio" name="answer" value="' . $answer . '" required />'
                     . $answer . '</label>';
        }

        $html .= '<input type="submit" value="Svara"/>
				  </form>';

        return $html;
    }
}
