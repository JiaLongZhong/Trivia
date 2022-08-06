<?php
//api consumer to pull from api and insert into db over question_queue

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('apiget_queue', false, false, false, false);

echo " [x] Awaiting RPC requests\n";
function api_call($n)
{
    $user_id = $n["userid"];
    $trivia_name = $n["trivia_name"];
    $log_file_name = "apiget_consumer.log";
    write_log("api_call from: " . $n['admin_email'], $log_file_name);
    echo "[xx] API call made\n";
    echo "[xx] Creating new trivia\n";
    $trivia_games = array();

    // send a curl request to the api url
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://opentdb.com/api.php?amount=" . $n["number_of_questions"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        //CURLOPT_POSTFIELDS => "apiKey=$api_key&newsSource=$source",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
        ),
    ));
    $api_response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $data = json_decode($api_response, true);
    $produced_data = array(
        "userid" => $user_id,
        "trivia_name" => $trivia_name,
        "number_of_questions" => $n["number_of_questions"],
        "admin_email" => $n["admin_email"],
        "data" => $data
    );
    // echo var_dump($data);
    //send data to db server
    require_once(__DIR__ . "/rpc_producer.php");
    $dbrpc = new RpcClient();
    $response = json_decode($dbrpc->call($produced_data, 'api_queue'), true);
    if ($response["status"] == "success") {
        $return_msg = array(
            "status" => "success",
            "trivia_games" => $response["trivia_games"]
        );
    } else {
        $return_msg = array(
            "status" => "error",
            "message" => "Error inserting trivia games"
        );
    }
    return $return_msg;
}


echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $n = $req->body;
    $consumed_data = json_decode($n, true);
    $return_msg = json_encode(api_call($consumed_data));
    $msg = new AMQPMessage(
        $return_msg,
        array('correlation_id' => $req->get('correlation_id'))
    );

    $req->delivery_info['channel']->basic_publish(
        $msg,
        '',
        $req->get('reply_to')
    );
    $req->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('apiget_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
    $channel->wait();
}
$channel->close();
$connection->close();
