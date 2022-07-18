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
    <?php
    //if post name is draft, echo post data
    if (isset($_POST["draft"])) {
        echo "<br><h4>This is a draft</h4>";
        echo var_dump($_POST);

        // loop through the questions and create an array of questions

        $questions = array();
        $i = 1;
        foreach ($_POST as $key => $value) {
            if (strpos($key, "question") !== false) {
                $questions[$i][$key] = $value;
                $i++;
            }
        }
        //output the questions array
        echo "<br><h4>This is the questions array</h4>";
        echo var_dump($questions);

        //loop through the correct answers and create an array of correct answers
        $correct_answers = array();
        $i = 1;
        foreach ($_POST as $key => $value) {
            if (strpos($key, "correct") !== false) {
                $correct_answers[$i][$key] = $value;
                $i++;
            }
        }
        //output the correct answers array
        echo "<br><h4>This is the correct answers array</h4>";
        echo var_dump($correct_answers);

        //loop through the incorrect answers and create an array of incorrect answers
        $incorrect_answers = array();
        $i = 1;
        $regex = "/answer\d+-q\d+/";
        foreach ($_POST as $key => $value) {
            // check is key matches regex pattern
            if (preg_match($regex, $key)) {
                $incorrect_answers[$i][$key] = $value;
                $i++;
            }
        }
        //output the incorrect answers array
        echo "<br><h4>This is the incorrect answers array</h4>";
        echo var_dump($incorrect_answers);
    } else if (isset($_POST["publish"])) {
        echo "<br><h4>This is a published trivia</h4>";
        echo var_dump($_POST);
        $question_count = 0;

        // loop through the questions and create an array of questions

        $questions = array();
        $i = 1;
        foreach ($_POST as $key => $value) {
            if (strpos($key, "question") !== false) {
                $questions[$i][$key] = $value;
                $i++;
            }
        }
        //count the number of questions
        $question_count = count($questions);

        //output the questions array
        echo "<br><h4>This is the questions array</h4>";
        echo var_dump($questions);
        // output total number of questions
        echo "<br><h4>This is the total number of questions</h4>";
        echo $question_count;

        //loop through the correct answers and create an array of correct answers
        $correct_answers = array();
        $i = 1;
        foreach ($_POST as $key => $value) {
            if (strpos($key, "correct") !== false) {
                $correct_answers[$i][$key] = $value;
                $i++;
            }
        }
        //output the correct answers array
        echo "<br><h4>This is the correct answers array</h4>";
        echo var_dump($correct_answers);

        //loop through the incorrect answers and create an array of incorrect answers
        $incorrect_answers = array();
        $i = 1;
        $regex = "/answer\d+-q\d+/";
        foreach ($_POST as $key => $value) {
            // check is key matches regex pattern
            if (preg_match($regex, $key)) {
                $incorrect_answers[$i][$key] = $value;
                $i++;
            }
        }
        //output the incorrect answers array
        echo "<br><h4>This is the incorrect answers array</h4>";
        echo var_dump($incorrect_answers);
    }


    if (isset($questions) && isset($correct_answers) && isset($incorrect_answers)) {
        $data = array(
            "questions" => $questions,
            "correct_answers" => $correct_answers,
            "incorrect_answers" => $incorrect_answers,
        );
        //output the data array
        echo "<br><h4>This is the data array</h4>";
        echo var_dump(json_encode($data, JSON_PRETTY_PRINT));
    }




    ?>

    <script>
        //Ready function of jQuery
        $(document).ready(function() {
            //create a counter
            var counter = 0;
            var input_count = $("#add-question-form input").length - 2;
            //DOM Cache
            var $add_question_container = $("#add-question-container");
            var $add_question_form = $("#add-question-form");
            var $add_question_button = $("#add-question");
            var $remove_question_button = $("#remove-question");
            var $question_submit = $("#question_submit");
            var $question_publish = $("#question_publish");
            var $add_question_button = $("#add-question");
            var $remove_question_button = $("#remove-question");


            //When the button is clicked, the form is appended
            $add_question_button.click(function() {
                // Add label and input for question
                console.log("button clicked");
                counter++;
                $add_question_container.append("<label id='question-label" + counter.toString() + "' for='question" + counter.toString() + "'>Question " + counter.toString() + "</label>");
                $add_question_container.append("<input type='text' id='question" + counter.toString() + "' name='question" + counter.toString() + "' />");
                //add button to add sub label and input for answer
                $add_question_container.append("<button id='add-answer" + counter.toString() + "' class='btn btn-success'>Add Answer</button>");
                //add a div to hold the sub label and input for answer
                $add_question_container.append("<div id='answer-container" + counter.toString() + "'></div>");
                input_count = $("#add-question-form input").length - 2;
                console.log(counter);
            });

            //When remove button is clicked, the last question is removed
            $remove_question_button.click(function() {
                console.log("button clicked");
                if (counter > 0) {
                    $("#question-label" + counter.toString()).remove();
                    $("#question" + counter.toString()).remove();
                    $("#number-of-answers" + counter.toString()).remove();
                    $("#add-answer" + counter.toString()).remove();
                    $("#answer-container" + counter.toString()).remove();

                    counter--;
                    input_count = $("#add-question-form input").length - 2;
                    console.log(counter);
                }
            });

            //When the add answer button is clicked, the form is appended
            $add_question_form.on("click", "button", function(e) {
                e.preventDefault();
                console.log("button clicked");
                var button_id = $(this).attr("id");
                var question_id = button_id.substring(10);
                console.log("Question ID " + question_id);
                console.log("Button ID " + button_id);
                $answer_container = $("#answer-container" + question_id);
                var answer_count = $("#answer-container" + question_id + " input").length + 1;
                console.log("Answer Count " + answer_count);
                // check if answer count is 1
                if (answer_count == 1) {
                    $answer_container.append("<label for='" + button_id + "-answer" + answer_count.toString() + "'>Correct Answer here:</label>");
                    $answer_container.append("<input type='text' id='" + button_id + "-answer" + answer_count.toString() + "' name='correct-q" + question_id.toString() + "' />");
                } else {
                    $answer_container.append("<label for='" + button_id + "-answer" + answer_count.toString() + "'>Answer " + answer_count.toString() + "</label>");
                    $answer_container.append("<input type='text' id='" + button_id + "-answer" + answer_count.toString() + "' name='answer" + answer_count.toString() + "-q" + question_id.toString() + "' />");
                }


            });



        });
    </script>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>

</body>

</html>