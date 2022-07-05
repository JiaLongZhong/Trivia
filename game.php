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
    <div id="game" class="flex-center flex-column" method="POST">
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
<script>
    document.getElementById('answer1').innerHTML = <?php echo json_encode($_POST['answer1']); ?>;
    document.getElementById('answer2').innerHTML = $_POST["ia2"];
    document.getElementById('answer3').innerHTML = $_POST["ia3"];
    document.getElementById('answer4').innerHTML = $_POST["ia4"];
    document.getElementById('question').innerHTML = $_POST["q"];
    document.getElementById('category').innerHTML = $_POST["c"];
    document.getElementById('difficulty').innerHTML = $_POST["d"];
    </script>
<?php
sleep(10);
if (isset($_POST["c"])) {
    $question = $_POST["q"];
    $answer1 = $_POST["ia1"];
    $answer2 = $_POST["ia2"];
    $answer3 = $_POST["ia3"];
    $answer4 = $_POST["ia4"];
    $category = $_POST["c"];
    $difficulty = $_POST["d"];
    $userID = get_user_id();
    $trivia_id = 1;
    //set html button texts to be answers set in php
    echo "<script>
    document.getElementById('answer1').innerHTML = '$answer1';
    document.getElementById('answer2').innerHTML = '$answer2';
    document.getElementById('answer3').innerHTML = '$answer3';
    document.getElementById('answer4').innerHTML = '$answer4';
    document.getElementById('question').innerHTML = '$question';
    document.getElementById('category').innerHTML = '$category';
    document.getElementById('difficulty').innerHTML = '$difficulty';
    </script>";

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