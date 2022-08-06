<?php

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('custom_trivia_queue', false, false, false, false);

function add_custom_questions($n)
{
    $user_id = null;
    $user_email = null;
    $trivia_id = null;
    $trivia_name = null;
    //echo var_dump($n);
    if (isset($n["userid"])) {
        $userid = $n["userid"];
    }
    if (isset($n["trivia_id"])) {
        $trivia_id = $n["trivia_id"];
    }
    if (isset($n["trivia_name"])) {
        $trivia_name = $n["trivia_name"];
    }
    if (isset($n["user_email"])) {
        $user_email = $n["user_email"];
    }
    $action = $n["action"];
    $action_id = null;
    if ($action == "draft") {
        $action_id = 1;
    } else if ($action == "publish") {
        $action_id = 2;
    } else if ($action == "delete") {
        $action_id = 3;
    }
    $db = null;
    $log_file_name = "custom_trivia_consumer.log";
    //write_log("Trivia ID: " . $trivia_id . "(" . $trivia_name . ")" . " edited by " . $user_email, $log_file_name);
    if ($action_id == 1 || $action_id == 2) {
        $db = getDB();
        $query = "UPDATE Trivia SET visibility = :status WHERE id = :id";
        $stmt = $db->prepare($query);
        $params = array(
            ":status" => $action_id,
            ":id" => $trivia_id
        );
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        if ($e[0] == "00000") {
            write_log("Trivia ID: " . $trivia_id . "(" . $trivia_name . ")" . " Updated visibility to " . $action_id, $log_file_name);
        } else {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("Trivia ID: " . $trivia_id . "(" . $trivia_name . ")" . " edited by " . $user_email . " error: " . $e[2], $log_file_name);
        }
    } else if ($action_id == 3) {
        //delete trivia and return response
        $db = getDB();
        $query = "DELETE FROM Trivia WHERE id = :id";
        $stmt = $db->prepare($query);
        $params = array(
            ":id" => $trivia_id
        );
        $r = $stmt->execute($params);
        $e = $stmt->errorInfo();
        if ($e[0] == "00000") {
            write_log("Trivia ID: " . $trivia_id . "(" . $trivia_name . ")" . " Deleted", $log_file_name);
            // get new trivia list
            $db = getDB();
            $query = "SELECT * FROM `Trivia` WHERE `user_id` = `:uid`";
            $stmt = $db->prepare($query);
            $params = array(
                ":uid" => $user_id
            );
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = array(
                "status" => "success",
                "message" => "Trivia deleted",
                "trivia" => $result
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => $e[2]
            );
            write_log("Trivia ID: " . $trivia_id . "(" . $trivia_name . ")" . " edited by " . $user_email . " error: " . $e[2], $log_file_name);
        }
        return $response;
    }

    $question_id = null;
    //echo count($n["questions"]) . "\n";
    for ($i = 1; $i <= count($n["questions"]); $i++) {
        // echo "\n" . $i . "\n";
        foreach ($n["questions"][$i] as $key => $value) {
            // echo $key . ": " . $value . "\n";
            if ($key == "question") {
                $question = $value;
                $query = "INSERT INTO Questions (question, trivia_id, user_id) VALUES (:question, :trivia_id, :user_id)";
                $stmt = $db->prepare($query);
                $params = array(
                    "question" => $question,
                    "trivia_id" => $trivia_id,
                    "user_id" => $user_id
                );
                $r = $stmt->execute($params);
                $e = $stmt->errorInfo();
                $question_id = $db->lastInsertId();
                if ($e[0] == "00000") {
                    write_log("Question created successfully", $log_file_name);
                    $query = "INSERT INTO Answers (answer, isCorrect, question_id, user_id) VALUES (:answer, :isCorrect, :question_id, :user_id)";
                    $stmt = $db->prepare($query);
                    $params = array(
                        ":answer" => $n["questions"][$i]["correct"],
                        ":isCorrect" => 1,
                        ":question_id" => $question_id,
                        ":user_id" => $user_id
                    );
                    $r = $stmt->execute($params);
                    $e = $stmt->errorInfo();
                    // There are 3 incorrect answers starting from 2 to 4
                    for ($j = 2; $j <= 4; $j++) {
                        $params = array(
                            ":answer" => $n["questions"][$i]["question" . $i . "_incorrect" . $j],
                            ":isCorrect" => 0,
                            ":question_id" => $question_id,
                            ":user_id" => $user_id
                        );
                        $r = $stmt->execute($params);
                        $e = $stmt->errorInfo();
                        if ($e[0] == "00000") {
                            write_log("Incorrect answer created successfully", $log_file_name);
                            $response = array(
                                "status" => "success",
                                "message" => "Created successfully"
                            );
                        } else {
                            $response = array(
                                "status" => "error",
                                "message" => $e[2]
                            );
                            write_log("Error creating incorrect answer: " . $e[2], $log_file_name);
                        }
                    }
                    return $response;
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => $e[2]
                    );
                    write_log("Question created error: " . $e[2], $log_file_name);
                }
                return $response;
            }
        }
    }
}


echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $n = $req->body;
    $consumed_data = json_decode($n, true);
    $return_msg = json_encode(add_custom_questions($consumed_data));
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
$channel->basic_consume('custom_trivia_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
    $channel->wait();
}
$channel->close();
$connection->close();
