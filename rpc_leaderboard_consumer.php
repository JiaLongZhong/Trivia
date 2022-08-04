<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once (__DIR__ . '/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('leaderboard_queue', false, false, false, false);


function LeaderboardSubmit($n)
{
	$log_file_name = "leaderboard_consumer.log";
	echo "pulling from leaderboard_queue\n";
	write_log("leaderboard Request from: " . $n['user'], $log_file_name);
	
	$db = getDB();
	$query = "SELECT
    u.fname,
    s.score,
    s.user_id
FROM
    Score s
JOIN Users u ON
    s.user_id = u.id
WHERE
    s.user_id = :user OR s.user_id =(
    SELECT
        sender_id
    FROM
        Friends
    WHERE
        receiver_id = :user
) OR s.user_id =(
    SELECT
        receiver_id
    FROM
        Friends
    WHERE
        sender_id = :user
)
ORDER BY
    s.score
DESC
    ";
	$stmt = $db->prepare($query);
	$params = array(
		'user' => $n['user']
	);
	$r = $stmt->execute($params);
	$e = $stmt->errorInfo();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($e[0] == "00000") {
		$response = array(
			"status" => "success",
			"leaderboard" => $result
		);
		write_log("leaderboard sent successfully: " . $n["user"], $log_file_name);
		
	} elseif ($e[0] == "23000") {
		$response = array(
			"status" => "error",
			"message" => "user already exists for that score"
		);
		write_log("leaderboard sent error: " . $e[2], $log_file_name);
	} else {
		$response = array(
			"status" => "error1",
			"message" => $e[2]
		);
		write_log("leaderboard sent error: " . $e[2], $log_file_name);
	}
	return $response;
}

echo " [x] Awaiting RPC requests for leaderboard\n";
$callback = function ($req) {
	$n = $req->body;
	$consumed_data = json_decode($n, true);
	$return_msg = json_encode(LeaderboardSubmit($consumed_data));
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
$channel->basic_consume('leaderboard_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
	$channel->wait();
}
$channel->close();
$connection->close();
