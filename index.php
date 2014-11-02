<?php

require_once('view/HTML5Base.php');
require_once('controller/QuizzyMaster.php');

session_start();

$router = new \controller\QuizzyMaster();
$html = new \view\Html5Base();

$html->getHTML($router->doRoute());