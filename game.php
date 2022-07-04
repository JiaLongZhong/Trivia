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
<body>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<div class="container">
    <div id="home" class="flex-center flex-column">
        <h1>Trivia Game</h1>
        <h3 id="category"></h3>
        <h3 id="difficulty"></h3>
        <h2 id="question"></h2>
        <div class="button-container">
            <p class="button-prefix">A</p>
            <p class="button-text" id="answer1"></p>
        </div>
        <div class="button-container">
            <p class="button-prefix">B</p>
            <p class="button-text" id="answer2"></p>
        </div>
        <div class="button-container">
            <p class="button-prefix">C</p>
            <p class="button-text" id="answer3"></p>
        </div>
        <div class="button-container">
            <p class="button-prefix">D</p>
            <p class="button-text" id="answer4"></p>
        </div>

    </div>
</div>

<?php include_once(__DIR__ . "/partials/footer.php"); ?>
<script src="static/js/game.js"></script>
</body>
</html>