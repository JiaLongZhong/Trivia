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
        <div class = "flex">
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
<script>
    document.getElementById('answer1').innerHTML = <?php echo json_encode($_GET['ia1']); ?>;
    document.getElementById('answer2').innerHTML = <?php echo json_encode($_GET['ia2']); ?>;
    document.getElementById('answer3').innerHTML = <?php echo json_encode($_GET['ia3']); ?>;
    document.getElementById('answer4').innerHTML = <?php echo json_encode($_GET['ia4']); ?>;
    document.getElementById('question').innerHTML = <?php echo json_encode($_GET['q']); ?>;
    document.getElementById('category').innerHTML = <?php echo json_encode($_GET['c']); ?>;
    document.getElementById('difficulty').innerHTML = <?php echo json_encode($_GET['d']); ?>;
    </script>
<?php

if (isset($_GET["c"])) {
    $question = $_GET["q"];
    $answer1 = $_GET["ia1"];
    $answer2 = $_GET["ia2"];
    $answer3 = $_GET["ia3"];
    $answer4 = $_GET["ia4"];
    $category = $_GET["c"];
    $difficulty = $_GET["d"];
    $userID = get_user_id();
    $trivia_id = 1;
    

    $question_array = array(
        "question" => $question,
        "answer1" => $answer1,
        "answer2" => $answer2,
        "answer3" => $answer3,
        "answer4" => $answer4,
        "category" => $category,
        "difficulty" => $difficulty,
        "user" => $userID,
        "trivia_id" => $trivia_id
    );

require_once(__DIR__ . "/rpc_producer.php");
$API_rpc = new RpcClient();
$response = json_decode($API_rpc->call($question_array, 'question_queue'), true);
echo var_dump($response);
if ($response["status"] == "error") {
    echo "Question Save unsuccessful";
} elseif ($response["status"]== "success") {
    echo "question saved";
    
    
    //resonse needs to reference the data entry from game.js 
    /*set_sess_var("question", $_POST["question"]);
    set_sess_var("category", $_POST["category"]);
    set_sess_var("difficulty", $_POST["difficulty"]);
    set_sess_var("answer1", $_POST["answer1"]);
    set_sess_var("answer2", $_POST["answer2"]);
    set_sess_var("answer3", $_POST["answer3"]);
    set_sess_var("answer4", $_POST["answer4"]);*/
    //header("Location:game.php");
}
}
?>
<?php include_once(__DIR__ . "/partials/footer.php"); ?>
<script src="static/js/game.js"></script>
</body>
</html>

