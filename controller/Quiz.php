<?php

namespace controller;

require_once('model/Question.php');
require_once('view/MessageHandler.php');
require_once('view/Quiz.php');
//TEMP
require_once('model/QuizDAL.php');

class Quiz {

    //private $model;
    private $view;
    //private $session;
    private $user;
    private $messageHandler;

    public function __construct(\model\User $user) {
        $this->user = $user;
        $this->view = new \view\Quiz();
        //$this->session = new \model\SessionHandler();
        $this->messageHandler = new \view\MessageHandler();
    }


    public function showQuizStatistics() {

        $quizId = $this->view->getQuizId();

        $quizDAL = new \model\QuizDAL();

        $quiz = $quizDAL->getQuizById($quizId);


        $quizResultArray = $quizDAL->getQuizResultArray($quizId);

        return $this->view->getQuizStatisticsHTML($quiz, $quizResultArray);
    }

    public function showUserStatistics() {

        $userId = $this->view->getUserId();
        $userDAL = new \model\UserDAL();
        $user = $userDAL->getUserById($userId);

        $quizDAL = new \model\QuizDAL();
        $userResultArray = $quizDAL->getUserResultArray($userId);
        
        return $this->view->getUserStatisticsHTML($user, $userResultArray);



    }


    public function manageQuiz() {

        $quizDAL = new \model\QuizDAL();

        if ($_GET['action'] == 'activatequiz' || $_GET['action'] == 'deactivatequiz') {
            $quizDAL->toogleQuizActivation($_GET['quiz']);
            $this->messageHandler->setMessage('Quiz-statusen är ändrad');
            //header('location: ' . \Settings::$ROOT . '?action=managequiz');
            $this->view->redirect(\view\QuizzyMaster::$MANAGE_QUIZ);
        }

        $quizes = $quizDAL->getAdminQuizes($this->user->getUserId());
        return $this->view->getAdminQuizListHTML($quizes);
    }

    public function showQuizResult() {

        // Hämta quizId från vyn get

        $quizId = $this->view->getQuizId();

        // Hämta  helt quiz samt resultat från DB

        $quizDAL = new \model\QuizDAL();

        $quiz = $quizDAL->getQuizById($quizId);
        $userAnswers = $quizDAL->getUserAnswersArray($this->user->getUserId(), $quizId);
        
        $result = $quizDAL->getQuizResult($quizId, $this->user->getUserId()); 

        return $this->view->getQuizResultHTML($quiz, $userAnswers, $result);
    }

    public function listAvalibleQuiz() {

        $dal = new \model\QuizDAL();
        $quizes = $dal->getAvalibleQuizes($this->user->getUserId());

        return $this->view->getAvalibleQuizListHTML($quizes);
    }

    public function listDoneQuiz() {

        $dal = new \model\QuizDAL();
        $quizes = $dal->getDoneQuizes($this->user->getUserId());

        return $this->view->getDoneQuizListHTML($quizes, $this->user->getUserId(), $dal);
    }

    public function createQuiz() {

        // Hämta eventuellt QuizId från GET
        $quizId = $this->view->getQuizId();

        // Kolla eventuellt att användaren är creator //
        // Annar skicka till felsida //
        
        // Kolla om QuizId fanns i GET
        if (empty($quizId)) {




            // Kolla om titel finns i POST
            if ($this->view->getQuizTitle() == false) { //(empty($this->view->getQuizTitle()))
                // Visa HTML för inmatning av titel
                return $this->view->getTitleFormHTML();
            } else {



                // Skapa ett Quiz-objekt
                $title = $this->view->getQuizTitle();
                $quiz = new \model\Quiz($title, $this->user->getUserId());

                $quizDAL = new \model\QuizDAL();
                $quizId = $quizDAL->saveQuiz($quiz);

                //header('location: '. \Settings::$ROOT . '?action=createquiz&quiz=' . $quizId);

                //var_dump($quizId); die();

                $this->view->redirectToQuiz(\view\QuizzyMaster::$CREATE_QUIZ, $quizId);
            }
        } elseif ($this->view->getQuestion()) { //!empty($this->view->getQuestion())

            // Hantera indatan --------------------------------------------------
            
            // Hämta input från användaren
            $question = $this->view->getQuestion();
            $answers = $this->view->getAnswers();
            
            // Kolla om användaren vill skicka med en bild
            $mediaPath = false;
            
            if(empty($_FILES["newFile"]["name"])) {
                
                $hasMedia = false; 
            } else {
                echo 'jag borde inte synas';
                $hasMedia = true;
                $mediaPath = $this->getImagePath();
            }
            
            if($hasMedia == false || $mediaPath != false) {
                // OM inga fel så Spara i DB --------------------------------------------------------------------

                // Hämta Quiz från DB
                $quizDAL = new \model\QuizDAL();
                $quiz = $quizDAL->getQuizById($quizId);

                // Skapa och lägg till Question i DB
                $questionObject = new \model\Question($question, $answers);

                if($hasMedia)
                    $questionObject->setMediaPath($mediaPath);

                $questionDAL = new \model\QuestionDAL();
                $questionDAL->saveQuestionByQuizId($questionObject, $quizId);

                //header('location: ' . \Settings::$ROOT . '?action=createquiz&quiz=' . $quizId);
                $this->view->redirectToQuiz(\view\QuizzyMaster::$CREATE_QUIZ, $quizId);
            }
        }

        $questionNumber = count((new \model\QuizDAL())->getQuizById($quizId)->getQuestions()) + 1;

        if ($this->view->isLastQuestion()) {
            //header('location: ' . \Settings::$ROOT . '?action=' . \view\QuizzyMaster::$PATH_MANAGE_QUIZ);
            $this->view->redirect(\view\QuizzyMaster::$MANAGE_QUIZ);
        } elseif ($questionNumber > 2)
            return $this->view->getQuestionFormHTML($questionNumber, $showDoneButton = true);
        else
            return $this->view->getQuestionFormHTML($questionNumber);
    }

    public function doQuiz() {

        // Hämta quizId från URL
        $quizId = $this->view->getQuizId();

        // Hämta Quiz och Questions från DB 
        $quizDAL = new \model\QuizDAL();
        $quiz = $quizDAL->getQuizById($quizId);
        $questions = $quiz->getQuestions();

        // Kolla om formulär har postats och spara då svaret i db
        if ($this->view->isPostBack()) {

            $doneQuizId = $quizDAL->getDoneQuizId($this->user->getUserId(), $quizId);

            if (empty($doneQuizId))
                $doneQuizId = $quizDAL->saveDoneQuiz($this->user->getUserId(), $quizId);

            // Hämta svar och questionId från vyn
            $answer = $this->view->getAnswer();
            $questionId = $this->view->getQuestionId();

            // Ta fram AnswerId baserat på svaret
            $questionDAL = new \model\QuestionDAL();
            $answerId = $questionDAL->getAnswerIdByQuestionIdAndAnswer($questionId, $answer);

            // Spara svaret		
            $questionDAL->saveUserAnswer($doneQuizId, $answerId);
        }

        // Kolla vilken Question som skall visas
        $quizSize = count($questions);
        $answerd = $quizDAL->getUserAnswersArray($this->user->getUserId(), $quizId);

        // Question id finns i useranswer så kolla nästa
        for ($i = 0; $i < $quizSize; $i++) {

            if (array_key_exists($questions[$i]->getQuestionId(), $answerd))
                continue;
            else {
                return $this->view->getHTML($questions[$i], $quizSize, $quiz->getQuizName(), $i + 1);
            }
        }

        $quizDAL->updateDoneQuizIsComplete($doneQuizId);

        //header('location: '. \Settings::$ROOT . '?action=' . \view\QuizzyMaster::$SHOW_RESULT . '&quiz=' . $quiz->getQuizId());
        $this->view->redirectToQuiz(\view\QuizzyMaster::$SHOW_RESULT, $quiz->getQuizId());
    }
    
    private function getImagePath(){
            
        $IMAGE_PATH = 'media/images/';
        
        $newFile = $_FILES["newFile"];

        if ($newFile["error"] !== UPLOAD_ERR_OK) {
            echo "<p>Ett fel inträffade vid uppladdning av bild</p>";
            return false;
        }

        // Ersätt olämpliga tecken med '_'
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $newFile["name"]);

        // Kolla om filnamn redan existrera
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists($IMAGE_PATH . $name)) {
            $i++;
            $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
        }

        // Lagra bilden på webbservern
        $success = move_uploaded_file($newFile["tmp_name"], $IMAGE_PATH . $name);
        
        // Skriv ut ett meddelande
        if (!$success) {
            echo "<p>Ett fel inträffade vid sparande av bildfilen</p>";
            return false;
        } else {
            echo "<p>Överföring av bild lyckades: $name </p>";
            return $IMAGE_PATH . $name;
        }
    }
}
