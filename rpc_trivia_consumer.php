<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('trivia_queue', false, false, false, false);

function create_trivia($n)
{
    $action = null;
    $trivia_id = null;
    if (isset($n["action"])) {
        $action = $n["action"];
    } else {
        $action = "create";
    }
    if (isset($n["trivia_id"])) {
        $trivia_id = $n["trivia_id"];
    }
    $user_id = $n["id"];
    $name = $n["trivia_name"];
    $description = $n["trivia_description"];
    $log_file_name = "trivia_consumer.log";
    write_log("Trivia Created by: " . $n['email'], $log_file_name);
    if ($action == "create") {
        $db = getDB();
        $query = "INSERT INTO Trivia(title, description, visibility, user_id) ";
        $query .= "VALUES(:name, :description, :visibility, :user_id)";
        $params = array(
            ":name" => $name,
            ":description" => $description,
            ":visibility" => 0,
            ":user_id" => $user_id
        );
        $stmt = $db->prepare($query);
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        if ($e[0] == "00000") {
            $query = "SELECT * FROM Trivia WHERE user_id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(array(":id" => $user_id));
            $trivia_games = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = array(
                "status" => "success",
                "message" => "Trivia created successfully",
                "trivia_games" => $trivia_games
            );
            write_log("Trivia created successfully", $log_file_name);
        } else {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("Trivia created error: " . $e[2], $log_file_name);
        }
        return $response;
    } else if ($action == "edit") {
        $db = getDB();
        $query = "UPDATE Trivia SET title = :name, description = :description WHERE id = :id";
        $params = array(
            ":name" => $name,
            ":description" => $description,
            ":id" => $trivia_id
        );
        $stmt = $db->prepare($query);
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        if ($e[0] == "00000") {
            $query = "SELECT * FROM Trivia WHERE user_id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(array(":id" => $user_id));
            $trivia_games = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = array(
                "status" => "success",
                "message" => "Trivia updated successfully",
                "trivia_games" => $trivia_games
            );
            write_log("Trivia updated successfully", $log_file_name);
        } else {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("Trivia updated error: " . $e[2], $log_file_name);
        }
        return $response;
    }
}

echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $n = $req->body;
    $consumed_data = json_decode($n, true);
    $return_msg = json_encode(create_trivia($consumed_data));
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
$channel->basic_consume('trivia_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
    $channel->wait();
}
$channel->close();
$connection->close();
