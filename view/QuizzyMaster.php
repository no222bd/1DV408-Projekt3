<?php

namespace view;

class QuizzyMaster {

    // Admin
    public static $MANAGE_USER = 'manageuser';
    public static $DELETE_USER = 'deleteuser';
    public static $MAKEADMIN = 'makeadmin';
    public static $MANAGE_QUIZ = 'managequiz';
    public static $CREATE_QUIZ = 'createquiz';
    public static $DELETE_QUIZ = 'deletequiz';
    public static $DEACTIVATE_QUIZ = 'deactivatequiz';
    public static $ACTIVATE_QUIZ = 'activatequiz';
    public static $QUIZ_STATS = 'quizstats';
    public static $USER_STATS = 'userstats';
    
    // User 
    public static $DO_QUIZ = 'doquiz';
    public static $LIST_AVALIBLE = 'avaliblequiz';
    public static $LIST_DONE = 'donequiz';
    public static $SHOW_RESULT = 'showresult';
    
    // Övriga
    public static $ACTION = 'action';
    public static $USER_ID = 'userId';
    public static $QUIZ_ID = 'quizId';
    public static $REGISTER = 'register';
    public static $HOME = 'home';

    private $user;

    public function __construct($user) {
        $this->user = $user;
    }

    public static function getAction() {

        if (isset($_GET[self::$ACTION])) {

            switch ($_GET[self::$ACTION]) {

                case self::$MANAGE_USER:
                    return self::$MANAGE_USER;
                    break;
                case self::$DELETE_USER:
                    return self::$DELETE_USER;
                    break;
                case self::$MAKEADMIN:
                    return self::$MAKEADMIN;
                    break;
                case self::$MANAGE_QUIZ:
                    return self::$MANAGE_QUIZ;
                    break;
                case self::$CREATE_QUIZ:
                    return self::$CREATE_QUIZ;
                    break;
                case self::$DEACTIVATE_QUIZ:
                    return self::$DEACTIVATE_QUIZ;
                    break;
                case self::$ACTIVATE_QUIZ:
                    return self::$ACTIVATE_QUIZ;
                    break;
                case self::$QUIZ_STATS:
                    return self::$QUIZ_STATS;
                    break;
                case self::$USER_STATS:
                    return self::$USER_STATS;
                    break;
                case self::$REGISTER:
                    return self::$REGISTER;
                    break;
                case self::$LIST_AVALIBLE:
                    return self::$LIST_AVALIBLE;
                    break;
                case self::$LIST_DONE:
                    return self::$LIST_DONE;
                    break;
                case self::$SHOW_RESULT:
                    return self::$SHOW_RESULT;
                    break;
                case self::$DO_QUIZ:
                    return self::$DO_QUIZ;
                    break;
                // default:
                //     return self::$HOME;
                //     break;
            }
        } else
            return self::$HOME;
    }

    public function getAdminHTML() {

        $html = '<h2>Välkommen ' . $this->user->getUsername() . '</h2>
                 
                 <a href="?' . self::$ACTION . '=' . self::$CREATE_QUIZ . '">Skapa quiz</a><br/>
                 <a href="?' . self::$ACTION . '=' . self::$MANAGE_QUIZ . '">Quizlista</a><br/>
                 <a href="?' . self::$ACTION . '=' . self::$MANAGE_USER . '">Användarlista</a><br/>'

                 . $this->getLogoutButtonHTML();

        return $html;
    }

    public function getUserHTML() {

        $html = '<h2>Välkommen ' . $this->user->getUsername() . '</h2>
                 
                 <a href="?' . self::$ACTION . '=' . self::$LIST_AVALIBLE . '">Tillgängliga quiz</a><br/>
                 <a href="?' . self::$ACTION . '=' . self::$LIST_DONE . '">Gjorda quiz</a><br/>'

                 . $this->getLogoutButtonHTML();

        return $html;
    }

    // Sträng beroende - name
    private function getLogoutButtonHTML() {
        return '<form class="logout" method="post">
                    <input type="submit" name="logout" value="Logga ut">
                </form>';
    }
}