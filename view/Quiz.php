<?php

namespace view;

require_once('view/Question.php');

class Quiz {

    private $messageHandler;

    public function __construct() {
        $this->messageHandler = new \view\MessageHandler();
    }

    public function redirectToUser($action, $id) {
        header('location: ' . \Settings::$ROOT . '?' . \view\QuizzyMaster::$ACTION . '=' . $action . '&'
                            . \view\QuizzyMaster::$USER_ID . '=' . $id);
    }

    public function redirectToQuiz($action, $id) {
        header('location: ' . \Settings::$ROOT . '?' . \view\QuizzyMaster::$ACTION . '=' . $action . '&'
                            . \view\QuizzyMaster::$QUIZ_ID . '=' . $id);
    }

    public function redirect($action) {
        header('location: ' . \Settings::$ROOT . '?' . \view\QuizzyMaster::$ACTION . '=' . $action);
    }


    public function getQuizId() {
        if (!empty($_GET[\view\QuizzyMaster::$QUIZ_ID]))
            return $_GET[\view\QuizzyMaster::$QUIZ_ID];
        else
            return NULL;
    }

    public function getUserId() {
        if (!empty($_GET[\view\QuizzyMaster::$USER_ID]))
            return $_GET[\view\QuizzyMaster::$USER_ID];
        else
            return NULL;
    }

    // Do Quiz ====================================================================================================

    public function isPostBack() {
        return $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer']);
    }

    public function getAnswer() {
        return $_POST['answer'];
    }

    public function getQuestionId() {
        return $_POST['questionId'];
    }

    public function getDoneQuizId() {
        return $_POST['doneQuizId'];
    }

    public function getResultHTML($correctCount, $questionIdex) {
        $html = '<p>Du hade ' . $correctCount . ' av ' . $questionIdex . ' r�tt!</p>';

        $html .= '<a href="?">Till huvudmenyn</a';

        return $html;
    }

    public function getHTML(\model\Question $question, $numberOfQuestions, $quizName, $questionNumber) {
        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>' . $quizName . '</h2>';

        $html .= '<div id="questionBox">'
                . (new \view\Question())->getHTML($question) .
                '</div>';

        $html .= '<h3>Fr�ga ' . $questionNumber . ' / ' . $numberOfQuestions . '</h3>';

        return $html;
    }

    // Create Quiz ====================================================================================================
    
    public function isLastQuestion() {
        return isset($_POST['lastQuestion']);
    }

    public function getQuizIdFromPOST() {
        if (isset($_POST['quizId']))
            return $_POST['quizId'];
    }

    public function getQuizTitle() {
        if (isset($_POST['quizTitle']))
            return $_POST['quizTitle'];
        else
            return NULL;
    }

    public function getQuestion() {
        if (isset($_POST['question']))
            return $_POST['question'];
        else
            return NULL;
    }

    public function getAnswers() {
        $answers = array();

        $answers[] = $_POST['answer1'];
        $answers[] = $_POST['answer2'];
        $answers[] = $_POST['answer3'];
        $answers[] = $_POST['answer4'];

        return $answers;
    }

    public function getTitleFormHTML() {
        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Skapa Quiz</h2>';

        $html .= '<p> Ange Quiz-namn </p>';

        $html .= '<form method="POST">
						<label>Quiznamn
							<input type="text" name="quizTitle" required />
						</label>
						<input type="submit" value="Skapa"/>
				  </form>';

        return $html;
    }

    public function getQuestionFormHTML($questionNumber, $showDoneButton = false) {

        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Skapa fr�ga ' . $questionNumber . '</h2>
				<form method="POST" enctype="multipart/form-data">
					<label> Ange fr�ga
						<input type="text" name="question" required />
					</label>
					<label> Ange svar
						<input type="text" name="answer1" required />
					</label>
					<hr>

					<input type="file" name="newFile"><br>

					<hr>
					<p>Om flervalsfr�ga �nskas, fyll i alla nedanst�ende</p-->
					<label> Ange svarsalternativ 1
						<input type="text" name="answer2" />
					</label>
					<label> Ange svarsalternativ 2
						<input type="text" name="answer3" />
					</label>
					<label> Ange svarsalternativ 3
						<input type="text" name="answer4" />
					</label>';

        if ($showDoneButton)
            $html .= '<input type="submit" value="Detta �r sista fr�gan" name="lastQuestion"/>';

        $html .= '<input type="submit" value="Ny fr�ga"/>
			</form>';

        return $html;
    }

    // List Quizes ====================================================================================================
    // public function getQuizListHTML($quizes) {
    // 	$html = '<h2>Tillg�ngliga Quiz</h2>';
    // 	foreach ($quizes as $quiz) {
    // 		$html .= '<p><a href="?action=' . \view\QuizzyMaster::$PATH_DO_QUIZ .  '&quiz=' . $quiz->getQuizId() . '">' . $quiz->getQuizName() . '</a></p>';
    // 	}
    // 	return $html;
    // }
    // Manage My Quizes ====================================================================================================

    public function getAdminQuizListHTML($quizes) {

        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Mina Quiz</h2>';

        if ($this->messageHandler->hasMessage())
            $html .= '<p>' . $this->messageHandler->getMessage() . '</p>';

        $html .= '<table class="listtable">
                      <thead>
                        <tr>
                          <th class="left">Quiznamn</th>
                          <th class="center">Storlek</th>
                          <th class="center">Ut�ka</th>
                          <th class="center">Status</th>
                        </tr>
                      </thead>
                      <tbody>';        

        foreach ($quizes as $quiz) {

            $html .= '<tr>
                        <td class="left"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$QUIZ_STATS . '&' . \view\QuizzyMaster::$QUIZ_ID . '=' . $quiz->getQuizId() . '">' . $quiz->getQuizName() . '</a></td>
					    <td class="center">' . count($quiz->getQuestions()) . '</td>
                        <td class="center"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$CREATE_QUIZ . '&' . \view\QuizzyMaster::$QUIZ_ID . '=' . $quiz->getQuizId() . '">+</a></td>';

            if ($quiz->getIsActive())
                $html .= '<td class="center"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$DEACTIVATE_QUIZ . '&' . \view\QuizzyMaster::$QUIZ_ID . '=' . $quiz->getQuizId() . '">Inaktivera</a></td>';
            elseif(count($quiz->getQuestions()) > 2)    // Str�ngberoende
                $html .= '<td class="center"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$ACTIVATE_QUIZ . '&' . \view\QuizzyMaster::$QUIZ_ID . '=' . $quiz->getQuizId() . '">Aktivera</a></td>';

            $html .= '</tr>';
        }

        $html .= '  </tbody>
                  </table>';

        return $html;
    }

    // Quiz Statistics ====================================================================================================

    public function getQuizStatisticsHTML($quiz, $resultArray) {
        
        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Quiz Statistik - ' . $quiz->getQuizname() . '</h2>';

        $html .= '<h3>Antal fr�gor: ' . count($quiz->getQuestions()) . '</h3>';


        $html .= '<table class="listtable">
                      <thead>
                        <tr>
                          <th class="left">Anv�ndare</th>
                          <th class="center">Antal r�tt</th>
                        </tr>
                      </thead>
                      <tbody>';

        foreach ($resultArray as $result) {

            $html .= '<tr>
                        <td class="left">' . $result[1] . '
                        <td class="center">' . $result[2] . '
                     </tr>';
        }

        $html .= '</tbody>
                  </table>';
    
        return $html;
    }


    // TODO
    // User Statistics ====================================================================================================

    public function getUserStatisticsHTML($user, $userResultArray) {

        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Statistik - ' . $user->getUsername() . '</h2>';

        $html .= '<h3>Antal quiz: ' . count($userResultArray) . '</h3>';


        $html .= '<table class="listtable">
                      <thead>
                        <tr>
                          <th class="left">Quiznamn</th>
                          <th class="center">Resultat</th>
                        </tr>
                      </thead>
                      <tbody>';

        foreach ($userResultArray as $result) {

            $html .= '<tr>
                        <td class="left">' . $result[0] . '
                        <td class="center">' . $result[1] . ' / ' . $result[2] . '
                     </tr>';
        }

        $html .= '</tbody>
                  </table>';
    
        return $html;
    }


    // List Avalible Quizes ====================================================================================================

    public function getAvalibleQuizListHTML($quizes) {

        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Tillg�ngliga Quiz</h2>';

        $html .= '<table class="listtable">
                      <thead>
                        <tr>
                          <th class="left">Quiznamn</th>
                          <th class="center">Antal fr�gor</th>
                        </tr>
                      </thead>
                      <tbody>';

        foreach ($quizes as $quiz) {
            $html .= '<tr>
                        <td class="left"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$DO_QUIZ . '&' . \view\QuizzyMaster::$QUIZ_ID . '=' . $quiz->getQuizId() . '">' . $quiz->getQuizName() . '</a></td>
                        <td class="center">' . count($quiz->getQuestions()) . '</td>
                      </tr>';
        }


        $html .= '  </tbody>
                  </table>';

        return $html;
    }

    // List Done Quizes ====================================================================================================

    public function getDoneQuizListHTML($quizes, $userId, $quizDAL) {

        $html = '<h3><a href="' . \Settings::$ROOT_PATH . '">Meny</a></h3><h2>Gjorda Quiz</h2>';


        $html .= '<table class="listtable">
                      <thead>
                        <tr>
                          <th class="left">Quiznamn</th>
                          <th class="center">Resultat</th>
                        </tr>
                      </thead>
                      <tbody>';

        foreach ($quizes as $quiz) {

            $html .= '<tr>
                        <td class="left"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$SHOW_RESULT . '&' . \view\QuizzyMaster::$QUIZ_ID . '=' . $quiz->getQuizId() . '">' . $quiz->getQuizName() . '</a></td>
                        <td class="center">' . $quizDAL->getQuizResult($quiz->getQuizId(), $userId) . ' av ' . count($quiz->getQuestions()) . '</td>
                      </tr>';
        }

        $html .= '  </tbody>
                  </table>';
        
        return $html;
    }

    // Quiz result html ====================================================================================================
    // PARAMETERTEST
    public function getQuizResultHTML($quiz, $userAnswers, $result) {

        $html = '<h3><a href="' . \Settings::$ROOT . '">Meny</a></h3><h2>Resultat - ' . $quiz->getQuizName() . '</h2>';

        $html .= '<p>Antal r�tt: ' . $result . ' / ' . count($quiz->getQuestions()) . '</p>';

        foreach ($quiz->getQuestions() as $question) {

            $html .= '<div class="result">
                    <h4>' . $question->getQuestion() . '</h4>';

            $html .= '<p>R�tt svar: ' . $question->getCorrectAnswer() . '</p>
					  <p>Ditt svar: ' . $userAnswers[$question->getQuestionId()] . '</p></div>';
        }

        return $html;
    }

}
