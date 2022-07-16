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
                $add_question_container.append("<label id='question-label" + counter.toString() + "' for='question'" + counter.toString() + ">Question " + counter.toString() + "</label>");
                $add_question_container.append("<input type='text' id='question" + counter.toString() + "' name='question' />");
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
                $answer_container.append("<label for='" + button_id + "-answer'" + answer_count.toString() + ">Answer " + answer_count.toString() + "</label>");
                $answer_container.append("<input type='text' id='" + button_id + "-answer" + answer_count.toString() + "' name='answer' />");

            });



        });
    </script>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>

</body>

</html>