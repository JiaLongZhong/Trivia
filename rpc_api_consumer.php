<?php 
//api consumer to pull from api and insert into db over question_queue
require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('API_queue', false, false, false, false);

#$n [ 'count' ] = how many they are pulling;
#$n [ 'user' ] = user id that is making these requests;
#$n [ 'trivia_id' ] = the id of where the questions are going to be added to;

function apiListener($n)
{
    $log_file_name = "api_log.log";
    echo " [x] Recieving RPC request\n";
    write_log("reccieving rpc request for api call", $log_file_name);

    //php base API pull using $n['count'] as the number of records to pull
    $api_pull = api_pull($n['count']);
    require_once(__DIR__ . "/rpc_producer.php");
    // rpc_producer to send to the game queue
    for ($i = 0; $i < $n['count']; $i++) {
        $API_question = new RpcClient();
        $sendarray = array(
            $question= $api_pull[$i]['question'],
            $question_id = "0",
            $user = $n["user"],
            $trivia_id = $n["trivia_id"],
            $answer1 = $api_pull[$i]['correct_answer'],
            $answer2 = $api_pull[$i]['incorrect_answers'][0],
            $answer3 = $api_pull[$i]['incorrect_answers'][1],
            $answer4 = $api_pull[$i]['incorrect_answers'][2]
        );

        $response = json_decode($API_question->call($sendarray, 'question_queue'), true);
        write_log("rpc_producer_api success: " . $response, $log_file_name);
        $channel->basic_publish($msg, '', $n['trivia_id']);
    }

    return $response;
}

function api_pull($m)
{
    $curl = curl_init();
    $url = "http://opentdb.com/api.php?amount=" . $m . "&type=multiple";
    $results = curl_exec($curl);
    $response = json_decode($results, true);
    $errors = $response['response_code'];
    $data = $response['results'];
    if ($errors) {
        echo "Error: " . $errors . "\n";
        write_log("Error: " . $errors, "api_log.log");
    } else {
        echo "Success: " . $response['response_code'] . "\n";
        write_log("Success: " . $response['response_code'], "api_log.log");
    }
    return $data;
}

$channel->basic_qos(null, 1, null);
$channel->basic_consume('API_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
	$channel->wait();
}
$channel->close();
$connection->close();