<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('trivia_info_queue', false, false, false, false);

function get_trivia($n)
{
    $trivia_id = $n["trivia_id"];
    echo "Received trivia id: " . $trivia_id . "\n";
    $db = getDB();
    $query = "SELECT * FROM Trivia JOIN Questions ON Trivia.id = Questions.trivia_id JOIN Answers ON Questions.id = Answers.question_id WHERE Trivia.id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute(array(":id" => $trivia_id));
    $trivia_games_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = array(
        "status" => "success",
        "message" => "Trivia retrieved successfully",
        "trivia_games_info" => $trivia_games_info
    );

    return $response;
}

echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $n = $req->body;
    $consumed_data = json_decode($n, true);
    $return_msg = json_encode(get_trivia($consumed_data));
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
$channel->basic_consume('trivia_info_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
    $channel->wait();
}
$channel->close();
$connection->close();
