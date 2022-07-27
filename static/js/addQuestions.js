//Ready function of jQuery
$(document).ready(function () {
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
    $add_question_button.click(function () {
        var $question_label;
        var $correct_answer_label;
        var $incorrect_answer_label;

        // Add label and input for question
        console.log("button clicked");
        counter++;
        //Labels
        $question_label = "question" + counter;
        $correct_answer_label = $question_label + "_correct";
        $incorrect_answer_label = $question_label + "_incorrect";
        //Inputs
        $add_question_container.append("<label id='question-label" + counter.toString() + "' for='question" + counter.toString() + "'>Question " + counter.toString() + "</label>");
        $add_question_container.append("<input type='text' id='question" + counter.toString() + "' name='question" + counter.toString() + "' />");
        //add button to add sub label and input for answer
        //add a div to hold the sub label and input for answer
        $add_question_container.append("<div id='answer-container" + counter.toString() + "'></div>");
        // add 4 labels and inputs for answer
        for (var i = 1; i <= 4; i++) {
            if (i == 1) {
                $("#answer-container" + counter.toString()).append("<label for=" + $correct_answer_label + ">Correct Answer</label>");
                $("#answer-container" + counter.toString()).append("<input type='text' id=" + $correct_answer_label + " name=" + $correct_answer_label + " />");
            } else {
                $("#answer-container" + counter.toString()).append("<label for=" + $incorrect_answer_label + ">Incorrect Answer " + i.toString() + "</label>");
                $("#answer-container" + counter.toString()).append("<input type='text' id=" + $incorrect_answer_label + " name=" + $incorrect_answer_label + i.toString() + " />");
            }
        }
        input_count = $("#add-question-form input").length - 2;
        console.log(counter);
    });

    //When remove button is clicked, the last question is removed
    $remove_question_button.click(function () {
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
    // Show edit form on button click
    $("#edit-trivia").click(function () {
        $("#edit-trivia-form").slideToggle();
    });
});