<?php
//set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
require_once(__DIR__ . "/lib/helpers.php");
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //get the score and trivia id
    $params = array(
    $score = $_GET['score'],
    $user = get_user_id(),
    $trivia = $_GET['trivia']
    );
    require_once(__DIR__ . "/rpc_producer.php");
    $score_rpc = new RpcClient();
    $response = json_decode($trivia_rpc->call($params, 'score_queue'), true);
    if ($response["status"] == "success") {
        success_msg("Score sent successfully");
        #header("Location: create_trivia.php");
    } else {
        error_msg("Error sending score");
        #header("Location: create_trivia.php");
    }

} else {
    // $json_object = array(
    //     "status" => "error",
    //     "result" => "Invalid request method"
    // );
    // echo json_encode($json_object);
    error_msg("Invalid request method");
}
?>
