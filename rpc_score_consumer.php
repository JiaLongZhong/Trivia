<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once (__DIR__ . '/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('score_queue', false, false, false, false);


function ScoreSubmit($n)
{
	$log_file_name = "score_consumer.log";
	write_log("score from: " . $n['user'], $log_file_name);
	$params = array(
		":score" => $n["score"],
		":user" => $n["user"],
		":trivia_id" => $n["trivia_id"]
	);
	$db = getDB();
	$query = "INSERT INTO Score(user, trivia_id, score) ";
	$query .= "VALUES(:user, :trivia_id, :score)";
	$stmt = $db->prepare($query);
	$r = $stmt->execute($params);
	$e = $stmt->errorInfo();
	if ($e[0] == "00000") {
		$response = array(
			"status" => "success",
			"message" => "Record added successfully"
		);
		write_log("score added success: " . $n["user"], $log_file_name);
		
	} elseif ($e[0] == "23000") {
		$response = array(
			"status" => "error",
			"message" => "user already exists for that score"
            #TODO add a check to see if the score is the same as the previous one and if so, don't add it again
		);
		write_log("score added error: " . $e[2], $log_file_name);
	} else {
		$response = array(
			"status" => "error1",
			"message" => $e[2]
		);
		write_log("score added error: " . $e[2], $log_file_name);
	}
	return $response;
}

echo " [x] Awaiting RPC requests for score\n";
$callback = function ($req) {
	$n = $req->body;
	$consumed_data = json_decode($n, true);
	$return_msg = json_encode(ScoreSubmit($consumed_data));
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
$channel->basic_consume('score_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
	$channel->wait();
}
$channel->close();
$connection->close();
