$(document).ready(function () {
    $("#create-trivia").click(function () {
        console.log("Create Trivia Button clicked");
        $("#create-trivia-form").slideToggle("slow", function () {
            console.log("SlideToggle complete");
        });
    });
});