<?php

namespace view;

class Html5Base {

    public function getHTML($body) {
        echo '<doctype html>
              <html lang="sv">
		      <head>
                <meta charset="iso-8859-1"/>
                <title>Quizzy</title>
                <link rel="stylesheet" type="text/css" href="style/normalize.css"/>
                <link rel="stylesheet" type="text/css" href="style/cikonss.css" />
                <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic"/>
                <link rel="stylesheet" type="text/css" href="style/style.css"/>
              <head>
        	  <body>
                <h1>Quizzy</h1>
                    ' . $body . '
		      </body>
		      </html>';
    }
}
