<?php 

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once(__DIR__ . '/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();
$channel->queue_declare('game_queue', false, true, false, false);
$log_file_name = "game_consumer.log";

function playGame($n)
{
    $log_file_name = "game_consumer.log";
    echo " [x] Requesting questions ($n)\n";
    write_log("pulling questions from db for game", $log_file_name);
    $data = get_questions($n);
}

function get_questions($n)
{
    $log_file_name = "game_consumer.log";
    $db = getDB();
    #$stmt = $db->prepare("SELECT * FROM questions WHERE trivia_id = :trivia_id ORDER BY RAND() LIMIT :count");
    $stmt = $db -> prepare("SELECT Questions.question, Answers.answer, Answers.isCorrect FROM Questions INNER JOIN Answers ON Questions.question_id = Answers.question_id WHERE Questions.trivia_id = :trivia_id");
    $params = array(
        ":trivia_id" => $n["trivia_id"],
    );
    $r = $stmt->execute($params);
    $e = $stmt->errorInfo();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = null;
    if ($e[0] != "00000") {
        $response = array(
            "status" => "error",
            "message" => $e[2]
        );
        write_log("get_questions error: " . $e[2], $log_file_name);
    } else {
        $response = array(
            "status" => "success",
            "questions" => $result
        );
        write_log("get_questions success: " . $n["trivia_id"], $log_file_name);
        
    }
}

echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
	$n = $req->body;
	$consumed_data = json_decode($n, true);
	$return_msg = json_encode(playGame($consumed_data));
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
$channel->basic_consume('game_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
	$channel->wait();
}
$channel->close();
$connection->close();
