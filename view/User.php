<?php

namespace view;

//require_once('view/MessageHandler.php');

class User {

    private $messageHandler;

    public function __construct() {
        $this->messageHandler = new \view\MessageHandler();
    }

    public function getUserListHTML(array $users) {

        $html = '<h2>Användare</h2>';

        if ($this->messageHandler->hasMessage())
            $html .= '<p>' . $this->messageHandler->getMessage() . '</p>';

        $html .= '<table class="listtable">
                    <thead>
                        <tr>
                            <th class="left">Användarnamn</th>
                            <th class="center">Ta bort</th>
                            <th class="center">Gör til Admin</th>
                        </tr>
                    </thead>
                    <tbody>';   

        foreach ($users as $user) {

            $html .= '<tr>
                        <td class="left"><a href="?' . \view\QuizzyMaster::$ACTION . '=' .  \view\QuizzyMaster::$USER_STATS . '&' . \view\QuizzyMaster::$USER_ID . '=' . $user->getUserId() . '">' . $user->getUsername() . '</a></td>
                        <td class="center"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$DELETE_USER . '&' . \view\QuizzyMaster::$USER_ID . '=' . $user->getUserId() . '">-</a></td>
                        <td class="center"><a href="?' . \view\QuizzyMaster::$ACTION . '=' . \view\QuizzyMaster::$MAKEADMIN . '&' . \view\QuizzyMaster::$USER_ID . '=' . $user->getUserId() . '">+</a></td>
                      </tr>';
        }

        $html .= '</tbody>
                </table>';

        return $html;
    }
}