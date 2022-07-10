<!DOCTYPE html>
<html lang="en">
<?php
require_once(__DIR__ . "/lib/helpers.php");
if (!is_logged_in()) {
    die(header("Location: index.php"));
}
?>
    <head>
        <meta charset="UTF-8">
        <title>Game Page</title>
        <?php require_once(__DIR__ . "/partials/gameheader.php"); ?>
    </head>
    <body class = "flex">
        <?php require_once(__DIR__ . "/partials/nav.php"); ?>
        <div class = "wrapper">
            <div class = "quiz-container">
                <div class = "quiz-head">
                    <h1 class = "quiz-title">Quiz Game</h1>
                    <div class = "quiz-score flex">
                        <span id = "correct-score"></span>/<span id = "total-question"></span>
                    </div>
                </div>
                <div class = "quiz-body">
                    <h2 class = "quiz-question" id = "question">
                        <!--What is the full form of HTTP? -->
                    </h2>
                    
                    <ul class = "quiz-options">
                        <!--<li>1. Hyper text transfer package</li>
                        <li>2. Hyper text transfer protocol</li>
                        <li>3. Hyphenation text test program</li>
                        <li>4. None of the above</li>-->
                    </ul>
                    <div id = "result">
                    </div>
                </div>
                <div class = "quiz-foot">
                    <button type = "button" id = "check-answer">Check Answer</button>
                    <button type = "button" id = "play-again">Play Again!</button>
                </div>
            </div>
        </div>
        <?php include_once(__DIR__ . "/partials/footer.php"); ?>
        <script src="scripts\game.js"></script>
    </body> 
</html>
