<?php

namespace model;

require_once('Settings.php');

abstract class SuperDAL {

	protected $dbConnection;

	// Quiz table properties
	protected static $quiz_tableName = 'quiz';
	protected static $quiz_idField = 'quizId';
	protected static $quiz_nameField = 'name';
	protected static $quiz_creatorIdField = 'creatorId';
	protected static $quiz_isActiveField = 'isActive';

	// DoneQuiz table properties
	protected static $done_tableName = 'donequiz';
	protected static $done_doneQuizIdField = 'doneQuizId';
	protected static $done_userIdField = 'userId';
	protected static $done_quizIdField = 'quizId';
	protected static $done_isCompleteField = 'isComplete';

	// Question table properties
	protected static $question_tableName = 'question';
	protected static $question_idField = 'questionId';
	protected static $question_quizIdField = 'quizId';
	protected static $question_questionField = 'question';

	// Answer table properties
	protected static $answer_tableName = 'answer';
	protected static $answer_idField = 'answerId';
	protected static $answer_questionIdField = 'questionId';
	protected static $answer_answerField = 'answer';
	protected static $answer_isCorrectField = 'isCorrect';

	// UserAnswer table properties
	protected static $userAnswer_tableName = 'useranswer';
	protected static $userAnswer_idField = 'userAnswerId';
	protected static $userAnswer_doneQuizIdField = 'doneQuizId';
	protected static $userAnswer_answerIdField = 'answerId';

	// MediaPath table properties
	protected static $mediaPath_tableName = 'mediapath';
	protected static $mediaPath_questionIdField = 'questionId';
	protected static $mediaPath_path = 'mediaPath';

	// User table properties
    protected static $tableName = 'user';
    protected static $userIdField = 'userId';
    protected static $usernameField = 'username';
    protected static $passwordField = 'password';
    protected static $isAdminField = 'isAdmin';
	
	protected function connectToDB() {
		
		if ($this->dbConnection == NULL)
			$this->dbConnection = new \PDO(\Settings::$DB_CONNECTIONSTRING, \Settings::$DB_USERNAME, \Settings::$DB_PASSWORD);
		
		//$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		
		return $this->dbConnection;
	}
}