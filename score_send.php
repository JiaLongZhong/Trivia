<?php
//set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
require_once(__DIR__ . "/lib/helpers.php");
//check for post request from js
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //get the data from the post request
    $data = array(
        "user" => get_user_id(),
        "trivia_id" => $_POST["trivia_id"],
        "score" => $_POST["score"]
    );
    //check if the data is valid
    //echo the data back to the client
    require_once(__DIR__ . "/rpc_producer.php");
    $score_rpc = new RpcClient();
    $response = json_decode($score_rpc->call($data, 'score_queue'), true);
    if ($response["status"] == "error") {
        echo json_encode(array("status" => "error", "message" => $response["message"]));
    } else {
        echo json_encode(array("status" => "success"));
    }
}
