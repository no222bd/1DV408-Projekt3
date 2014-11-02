<?php

namespace model;

require_once('model/SuperDAL.php');

class QuestionDAL extends \model\SuperDAL {

	protected static $userAnswer_tableName = 'useranswer';
	protected static $userAnswer_idField = 'userAnswerId';
	protected static $userAnswer_doneQuizIdField = 'doneQuizId';
	protected static $userAnswer_answerIdField = 'answerId';

	
	public function saveUserAnswer($doneQuizId, $answerId) {

		$this->connectToDB();

		$sql = 'INSERT INTO ' . self::$userAnswer_tableName . ' (' .self::$userAnswer_doneQuizIdField . ', ' . self::$userAnswer_answerIdField . ') 
				VALUES (:doneQuiz_Id, :answer_Id)';

		$stmt = $this->dbConnection->prepare($sql);

		$stmt->execute(array(
			'doneQuiz_Id' => $doneQuizId,
			'answer_Id' => $answerId)
		);
	}

	public function getAnswerIdByQuestionIdAndAnswer($questionId, $answer) {

		$this->connectToDB();

		$sql = 'SELECT ' . self::$answer_idField . '
				FROM ' . self::$answer_tableName . '
				WHERE ' . self::$answer_questionIdField . '=:question_Id AND ' . self::$answer_answerField . '=:answer';

		$stmt = $this->dbConnection->prepare($sql);

		$stmt->execute(array('question_Id' => $questionId,
							 'answer' => $answer)
		);
	
		$answerId = $stmt->fetch();

		return $answerId[0];
	}

	public function populateQuizObject($quiz) {

		$this->connectToDB();

		$sql = 'SELECT *
				FROM ' . self::$question_tableName . '
				WHERE ' . self::$question_quizIdField . ' = :quiz_Id';

		$stmt = $this->dbConnection->prepare($sql);
	
		$stmt->execute(array('quiz_Id' => $quiz->getQuizId()));

		while($row = $stmt->fetch()) {
			$answers = $this->getAnswersByQuestionId($row[self::$question_idField]);
			$question = new \model\Question($row[self::$question_questionField], $answers);
			$question->setQuestionId($row[self::$question_idField]);

			// Set path if exists
			$question->setMediaPath($this->getMediaPath($row[self::$question_idField]));

			$quiz->addQuestion($question);
		}
	}

	public function getMediaPath($questionId) {
		
		// Kolla i Media om questionId har media
		$sql = 'SELECT *
				FROM ' . self::$mediaPath_tableName . '
				WHERE ' . self::$mediaPath_questionIdField . ' = :question_Id';

		$stmt = $this->dbConnection->prepare($sql);
	
		$stmt->execute(array('question_Id' => $questionId));
		
		$result = $stmt->fetch();

		if($result)
                    return $result[self::$mediaPath_path];
	}

	private function getAnswersByQuestionId($questionId) {

		$this->connectToDB();

		$sql = 'SELECT * FROM ' . self::$answer_tableName . '
				WHERE ' . self::$answer_questionIdField . ' = :question_Id
				ORDER BY ' . self::$answer_isCorrectField . ' DESC';

		$stmt = $this->dbConnection->prepare($sql);

		$stmt->execute(array('question_Id' => $questionId));
	
		$answersArray = array();

		while($row = $stmt->fetch()) {
			$answersArray[] = $row[self::$answer_answerField];
		}

		return $answersArray;
	}

	// Osäker på namnet/parameter
	public function saveQuestionByQuizId(\model\Question $question, $quizId) {
		
		//Sparar i Question och tar emot Id
		
		$this->connectToDB();

		$sql = 'INSERT INTO ' . self::$question_tableName . ' (' . self::$question_quizIdField . ', ' . self::$question_questionField . ') 
				VALUES (:quiz_Id, :question)';

		$stmt = $this->dbConnection->prepare($sql);
	
		$stmt->execute(array(
				'quiz_Id' => $quizId,
				'question' => $question->getQuestion())
			);

		$questionId = $this->dbConnection->lastInsertId();

		// Spar eventuell mediaPath
		if(!is_null($question->getMediaPath()))
			$this->saveMediaPath($questionId, $question->getMediaPath());

		// Sparar Answers
		$answers = $question->getAnswers();
		$numberOfAnswers = count($answers);

		// Alternativt kan man kör pop på arrayen
		for($i = 0; $i < $numberOfAnswers; $i++) {

			if($i == 0)
				$isCorrect = TRUE;
			else
				$isCorrect = FALSE;

			$this->saveAnswer($questionId, $answers[$i], $isCorrect);
		}
	}

	private function saveMediaPath($questionId, $mediaPath) {

		$this->connectToDB();

		$sql = 'INSERT INTO ' . self::$mediaPath_tableName . ' (' .self::$mediaPath_questionIdField . ', ' . self::$mediaPath_path . ')
				VALUES (:question_Id, :media_Path)';

		$stmt = $this->dbConnection->prepare($sql);

		$stmt->execute(array(
			'question_Id' => $questionId,
			'media_Path' => $mediaPath)
		);
	}

	// Kanske bör ta array eller hela Questionobjekt
	private function saveAnswer($questionId, $answer, $isCorrect) {

		$this->connectToDB();

		$sql = 'INSERT INTO ' . self::$answer_tableName . ' (' .self::$answer_questionIdField . ', '
						      . self::$answer_answerField . ', ' . self::$answer_isCorrectField . ') 
				VALUES (:question_Id, :answer, :is_Correct)';

		$stmt = $this->dbConnection->prepare($sql);

		$stmt->execute(array(
			'question_Id' => $questionId,
			'answer' => $answer,
			'is_Correct' => $isCorrect)
		);
	}

	// public function getQuestionById($questionId) {

	// 	$this->connectToDB();

	// 	$sql = 'SELECT *
	// 			FROM ' . self::$question_tableName . '
	// 			WHERE ' . self::$question_idField . ' = :question_Id';

	// 	$stmt = $this->dbConnection->prepare($sql);
	
	// 	$stmt->execute(array('question_Id' => $questionId));

	// 	$result = $stmt->fetch();
		
	// 	$answers = $this->getAnswersByQuestionId($questionId);

	// 	$question = new \model\Question($result[self::$question_questionField], $answers);

	// 	$question->setQuestionId($result[self::$question_idField]);

	// 	//if( has media)

	// 	return $question;
	// }
}