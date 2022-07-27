<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>

<body>
    <?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <?php
    if (!has_role("trivia_creator")) {
        echo "You do not have permission to access this page";
        die();
        show_flash_messages();
    }
    ?>
    <h2 class="text-uppercase"><?php echo $_GET["title"]; ?></h2>
    <?php
    if (isset($_GET["id"])) {
        $trivia_id = $_GET["id"];
        $trivia_name = $_GET["title"];
        $user_id = get_user_id();

        $isValid = true;

        if (!isset($trivia_id) || !isset($trivia_name) || !isset($user_id)) {
            $isValid = false;
        }

        if ($isValid) {
            require_once(__DIR__ . "/rpc_producer.php");
            $trivia_rpc = new RpcClient();
            $response = json_decode($trivia_rpc->call(array("trivia_id" => $trivia_id), 'trivia_info_queue'), true);
            if ($response["status"] == "success") {
                set_sess_var("trivia_info", $response["trivia_games_info"]);
            }
        }
    }
    ?>

    <!-- create a div with a button -->
    <div>
        <button id="add-question" class="btn btn-success">Add Question</button>
        <button id="remove-question" class="btn btn-danger">Remove Question</button>
        <!-- create a form with a question field -->
        <form id="add-question-form" class="form" method="POST">
            <div id="add-question-container">

            </div>
            <input type="submit" id="question_submit" name="draft" value="Save Draft" />
            <input type="submit" id="question_publish" name="publish" value="Publish" />
            <input class="btn btn-danger" type="submit" id="question_delete" name="delete" value="Delete Trivia" />
        </form>
    </div>
    <?php
    // Function to map questions correctly
    function map_questions($data)
    {
        if (isset($data["draft"])) {
            $action = "draft";
        } else if (isset($data["publish"])) {
            $action = "publish";
        } else {
            $action = "delete";
        }

        $regex_question = "/^question(\d+)$/";
        $regex_correct = "/correct$/";
        $regex_incorrect = "/question(\d+)_incorrect(\d+)$/";
        $return_array = array();
        $return_array["action"] = $action;
        $return_array["trivia_id"] = $_GET["id"];
        $return_array["userid"] = get_user_id();
        $return_array["trivia_name"] = $_GET["title"];
        $return_array["user_email"] = get_email();
        $return_array["questions"] = array();
        $question_counter = 0;
        foreach ($data as $key => $value) {

            if (preg_match($regex_question, $key)) {
                $question_counter++;
                $return_array["questions"][$question_counter]["question"] = $value;
            }
            if (preg_match($regex_correct, $key)) {
                $return_array["questions"][$question_counter]["correct"] = $value;
            }
            if (preg_match($regex_incorrect, $key)) {
                $return_array["questions"][$question_counter][$key] = $value;
            }
        }
        return $return_array;
    }

    //Save the draft of the trivia game
    if (isset($_POST["draft"])) {
        $trivia_questions = map_questions($_POST);
        require_once(__DIR__ . "/rpc_producer.php");
        $trivia_rpc = new RpcClient();
        $response = json_decode($trivia_rpc->call($trivia_questions, 'custom_trivia_queue'), true);
        if (isset($response) && $response["status"] == "success") {
            success_msg("Trivia draft saved successfully");
            header("Location: create_trivia.php");
        } else {
            error_msg("Error saving trivia draft");
        }
    }
    // Publish the trivia game
    if (isset($_POST["publish"])) {
        $trivia_questions = map_questions($_POST);
        require_once(__DIR__ . "/rpc_producer.php");
        $trivia_rpc = new RpcClient();
        $response = json_decode($trivia_rpc->call($trivia_questions, 'custom_trivia_queue'), true);
        if (isset($response) && $response["status"] == "success") {
            success_msg("Trivia published successfully");
            header("Location: create_trivia.php");
        } else {
            error_msg("Error publishing trivia");
        }
    }

    if (isset($_POST["delete"])) {
        $trivia_questions = map_questions($_POST);
        require_once(__DIR__ . "/rpc_producer.php");
        $trivia_rpc = new RpcClient();
        $response = json_decode($trivia_rpc->call($trivia_questions, 'custom_trivia_queue'), true);
        if (isset($response) && $response["status"] == "success") {
            success_msg("Trivia deleted successfully");
            header("Location: create_trivia.php");
        } else {
            error_msg("Error deleting trivia");
        }
    }
    ?>
    <!-- Create a div to display the current questions and answers of the trivia game -->
    <div id="trivia-questions">
        <?php
        $trivia_info = get_trivia_info();
        $q_id = null;
        if (isset($trivia_info)) {
            $question_counter = 0;
            foreach ($trivia_info as $game) {

                if ($q_id != $game["question_id"]) {
                    $q_id = $game["question_id"];
                    $question_counter++;
                }

                echo "<h4>" . $question_counter . "</h4>";
                echo "<h2>" . $game["question"] . "</h2>";
                echo "<p>" . $game["answer"] . "</p>";
                if ($game["isCorrect"] == 1) {
                    echo "<p class='correct'>Correct!</p>";
                } else {
                    echo "<p class='incorrect'>Incorrect!</p>";
                }
                $q_id = $game["question_id"];
            }
        }
        ?>
    </div>
    <script src="static/js/addQuestions.js" defer></script>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>

</body>

</html>