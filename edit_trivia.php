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
    }
    ?>
    <h2 class="text-uppercase"><?php echo $_GET["title"]; ?></h2>
    <!-- Create button to bring up edit form -->
    <button id="edit-trivia">Edit Name</button>
    <form id="edit-trivia-form" class="form" method="POST" style="display: none;">
        <label for="trivia-name">Name</label>
        <input type="text" id="trivia-name" name="trivia_name" />
        <br>
        <label for="description">Description</label>
        <input type="text" id="trivia-description" name="trivia_description" />
        <br>
        <input type="submit" id="trivia_submit" name="edit" value="Edit" />
    </form>
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

        if (isset($_POST["edit"])) {
            $trivia_name = $_POST["trivia_name"];
            $trivia_description = $_POST["trivia_description"];
            $user_id = get_user_id();
            $action = "edit";
            $isValid = true;
            if (!isset($trivia_name) || !isset($trivia_description)) {
                $isValid = false;
            }
            if ($isValid) {
                require_once(__DIR__ . "/rpc_producer.php");
                $trivia_rpc = new RpcClient();
                $response = json_decode($trivia_rpc->call(
                    array(
                        "trivia_id" => $trivia_id,
                        "trivia_name" => $trivia_name,
                        "trivia_description" => $trivia_description,
                        "id" => $user_id,
                        "action" => $action
                    ),
                    'trivia_queue'
                ), true);
                if ($response["status"] == "success") {
                    success_msg("Trivia edited successfully");
                    set_sess_var("trivia_games", $response["trivia_games"]);
                    header("Location: create_trivia.php");
                } else {
                    error_msg("Error editing trivia");
                    header("Location: create_trivia.php");
                }
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
        </form>
    </div>
    <!-- Create a div to display the current questions and answers of the trivia game -->
    <div id="trivia-questions">
        <?php
        list_trivia_info();
        ?>
    </div>
    <?php

    function map_questions($data)
    {
        if (isset($data["draft"])) {
            $action = "draft";
        } else {
            $action = "publish";
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
        if ($response["status"] == "success") {
            echo "Draft saved successfully";
        } else {
            echo "Draft failed to save";
        }
    }
    // Publish the trivia game
    if (isset($_POST["publish"])) {
        $trivia_questions = map_questions($_POST);
        require_once(__DIR__ . "/rpc_producer.php");
        $trivia_rpc = new RpcClient();
        $response = json_decode($trivia_rpc->call($trivia_questions, 'custom_trivia_queue'), true);
        if ($response["status"] == "success") {
            echo "Trivia game published successfully";
        } else {
            echo "Trivia game failed to publish";
        }
    }

    ?>
    <script src="static/js/addQuestions.js" defer></script>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>

</body>

</html>