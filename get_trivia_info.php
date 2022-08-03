<?php
//set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");

// check to see if the request is a GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // get the trivia id from the url
    $trivia_id = $_GET['trivia_id'];
    // create rpc producer
    require_once(__DIR__ . "/rpc_producer.php");
    $trivia_info_rpc = new RpcClient();
    // create the array to send to the rpc server
    $trivia_info_array = array(
        "trivia_id" => $trivia_id
    );
    // call the rpc server
    $response = json_decode($trivia_info_rpc->call($trivia_info_array, 'trivia_info_queue'), true);
    // check to see if the response is successful
    if ($response["status"] == "success") {
        // get the trivia info
        $trivia_info = $response["trivia_games_info"];
        // check to see if the trivia info is empty
        if (count($trivia_info) == 0) {
            // if the trivia info is empty, return an error
            echo json_encode(array("status" => "error", "message" => "No trivia info found"));
        } else {
            $q_id = null;
            $q_counter = 0;
            $q_question = null;
            $q_number = 0;
            $json_array = array();
            // loop through the trivia info
            foreach ($trivia_info as $question) {
                if ($question["question_id"] != $q_id && $question["question"] != $q_question) {
                    $q_id = $question["question_id"];
                    $q_question = $question["question"];
                    $q_counter++;
                    $json_array[$q_counter] = array(
                        "question_id" => $q_id,
                        "question" => $q_question,
                        "answers" => array()
                    );
                }

                if ($q_counter != $q_number) {
                    $q_number = $q_counter;
                    $json_array[$q_counter]["answers"]["correct"] = $question["answer"];
                } else {
                    $json_array[$q_counter]["answers"][] = $question["answer"];
                }
            }
        }
        echo json_encode($json_array);

        // if the trivia info is not empty, return the trivia info
        //echo json_encode(array("status" => "success", "result" => $trivia_info));
    }
} else {
    // create the json object
    $json_object = array(
        "status" => "error",
        "result" => $response["message"]
    );
    // encode the json object
    $json_encoded = json_encode($json_object);
    // send the json object
    echo $json_encoded;
}
