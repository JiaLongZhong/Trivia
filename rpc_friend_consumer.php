<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('friend_queue', false, false, false, false);

function processFriendInfo($n)
{
    $log_file_name = "friend_consumer.log";
    echo "\n**Querying database\n";
    write_log("processFriendInfo from: " . $n['user_id'], $log_file_name);
    if (isset($n["query"])) {
        $friend_name = $n["query"];
        $db = getDB();
        $query = "SELECT Users.id, Users.username FROM Users WHERE username LIKE :friend_name AND id != :user_id AND is_active = 1";
        $stmt = $db->prepare($query);
        $params = array(":friend_name" => "%$friend_name%", ":user_id" => $n["user_id"]);
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = null;
        if ($e[0] != "00000") {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("processFriendInfo error: " . $e[2], $log_file_name);
        } else {
            $response = array(
                "status" => "success",
                "message" => "Found " . count($result) . " users",
                "result" => $result
            );
            write_log("processFriendInfo success", $log_file_name);
        }
        return $response;
    }
    if (isset($n["friend_id"]) && !isset($n["action"])) {
        $friend_id = $n["friend_id"];
        $db = getDB();
        $query = "INSERT INTO Friends (sender_id, receiver_id) VALUES (:user_id, :friend_id)";
        $stmt = $db->prepare($query);
        $params = array(":user_id" => $n["user_id"], ":friend_id" => $friend_id);
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        $response = null;
        if ($e[0] != "00000") {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("processFriendInfo error: " . $e[2], $log_file_name);
        } else {
            $response = array(
                "status" => "success",
                "message" => "Request sent"
            );
            write_log("processFriendInfo success", $log_file_name);
        }
        return $response;
    }
    if (isset($n["user_id"]) && !isset($n["query"]) && !isset($n["friend_id"])) {
        $user_id = $n["user_id"];
        $db = getDB();
        $query = "SELECT Users.username, Friends.id, Friends.sender_id, Friends.receiver_id, Friends.status FROM Friends JOIN Users ON Friends.sender_id = Users.id WHERE Friends.receiver_id = :user_id";
        $stmt = $db->prepare($query);
        $params = array(":user_id" => $user_id);
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = null;
        if ($e[0] != "00000") {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("processFriendInfo error: " . $e[2], $log_file_name);
        } else {
            $response = array(
                "status" => "success",
                "message" => "Found " . count($result) . " friend requests",
                "result" => $result
            );
            write_log("processFriendInfo success", $log_file_name);
        }
        return $response;
    }
    if (isset($n["user_id"]) && isset($n["friend_id"]) && isset($n["action"])) {
        $user_id = $n["user_id"];
        $friend_id = $n["friend_id"];
        $action = $n["action"];
        write_log("processFriendInfo action: " . $action, $log_file_name);


        $db = getDB();
        $query = "UPDATE Friends SET status = :action WHERE sender_id = :friend_id AND receiver_id = :user_id";
        $stmt = $db->prepare($query);
        $params = array(":user_id" => $user_id, ":friend_id" => $friend_id, ":action" => $action);
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        $response = null;
        if ($e[0] != "00000") {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("processFriendInfo error: " . $e[2], $log_file_name);
        } else {
            $query = "INSERT INTO Friends (sender_id, receiver_id, status) VALUES (:friend_id, :user_id, :action)";
            $stmt = $db->prepare($query);
            $params = array(":user_id" => $friend_id, ":friend_id" => $user_id, ":action" => $action);
            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                $response = array(
                    "status" => "error",
                    "message" => $e[2]
                );
                write_log("processFriendInfo error: " . $e[2], $log_file_name);
            } else {
                $response = array(
                    "status" => "success",
                    "message" => "Friend request " . $action
                );
                write_log("processFriendInfo success", $log_file_name);
            }
        }
        return $response;
    }
}

echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $n = $req->body;
    $consumed_data = json_decode($n, true);
    $return_msg = json_encode(processFriendInfo($consumed_data));
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
$channel->basic_consume('friend_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
    $channel->wait();
}
$channel->close();
$connection->close();
