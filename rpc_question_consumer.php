<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('question_queue', false, false, false, false);

function answerSubmit($m){
	$log_file_name = "question_consumer.log";
	write_log("answerSubmit from: " . $m['user_id'], $log_file_name);
	$params = array(
		":answer" => $m["answer"],
		":question_id" => $m["question_id"],
		":user_id" => $m["user_id"],
		":isCorrect" => $m["isCorrect"]
		
	);
	//$type = $data[":type"];
	$db = getDB();
	$query = "INSERT INTO Answers(answer, isCorrect, question_id, user_id) ";
	$query .= "VALUES(:answer, :isCorrect, :question_id, :user_id)";
	$stmt = $db->prepare($query);
	$r = $stmt->execute($params);
	$e = $stmt->errorInfo();
	if ($e[0] == "00000") {
		$response = array(
			"status" => "success",
			"message" => "Record added successfully"
		);
		write_log("answerSubmit success: " . $m["user_id"], $log_file_name);
	} elseif ($e[0] == "23000") {
		$response = array(
			"status" => "error",
			"message" => "Email or username already exists"
		);
		write_log("answerSubmit error: " . $e[2], $log_file_name);
	} else {
		$response = array(
			"status" => "error1",
			"message" => $e[2]
		);
		write_log("answerSubmit error: " . $e[2], $log_file_name);
	}
	return $response;
}
function QuestionSubmit($n)
{
	$log_file_name = "question_consumer.log";
	write_log("question from: " . $n['user'], $log_file_name);
	$params = array(
		":question" => $n["question"],
		":user" => $n["user"],
		":trivia_id" => $n["trivia_id"]
	);
	//$type = $data[":type"];
	$db = getDB();
	$query = "INSERT INTO Questions(question, user_id, trivia_id) ";
	$query .= "VALUES(:question, :user, :trivia_id)";
	$stmt = $db->prepare($query);
	$r = $stmt->execute($params);
	$e = $stmt->errorInfo();
	if ($e[0] == "00000") {
		$response = array(
			"status" => "success",
			"message" => "Record added successfully"
		);
		write_log("question added success: " . $n["user"], $log_file_name);
		//submit the answers to the answer table
		answerSubmit(array( "user_id" => $n["user"], "question_id" => $n["question_id"], "answer" => $n["answer1"], "isCorrect" => 1));
		answerSubmit(array( "user_id" => $n["user"], "question_id" => $n["question_id"], "answer" => $n["answer2"], "isCorrect" => 0));
		answerSubmit(array( "user_id" => $n["user"], "question_id" => $n["question_id"], "answer" => $n["answer3"], "isCorrect" => 0));
		answerSubmit(array( "user_id" => $n["user"], "question_id" => $n["question_id"], "answer" => $n["answer4"], "isCorrect" => 0));

	} elseif ($e[0] == "23000") {
		$response = array(
			"status" => "error",
			"message" => "Email or username already exists"
		);
		write_log("question added error: " . $e[2], $log_file_name);
	} else {
		$response = array(
			"status" => "error1",
			"message" => $e[2]
		);
		write_log("question added error: " . $e[2], $log_file_name);
	}
	return $response;
}

echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
	$n = $req->body;
	$consumed_data = json_decode($n, true);
	$return_msg = json_encode(QuestionSubmit($consumed_data));
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
$channel->basic_consume('question_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
	$channel->wait();
}
$channel->close();
$connection->close();
