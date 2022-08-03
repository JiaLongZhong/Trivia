$(document).ready(function () {
    // make ajax call to get trivia data
    $.ajax({
        url: "get_trivia_info.php",
        type: "GET",
        dataType: "json",
        data: {
            "trivia_id": "8"
        },
        success: function (data) {
            console.log(data);
        }
    });
});