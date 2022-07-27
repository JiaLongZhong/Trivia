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
    $stmt = $db -> prepare("SELECT Questions.question, Answers.answer, Answers.isCorrect FROM Questions INNER JOIN Answers ON Questions.question_id = Answers.question_id WHERE Questions.trivia_id = :trivia_id LIMIT :count");
    $params = array(
        ":trivia_id" => $n["trivia_id"],
        ":count" => $n["count"]
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
        //go through each question and get the answers || do seperate call for each question from APP 
        /*foreach ($result as $question) {
            $stmt = $db->prepare("SELECT * FROM answers WHERE question_id = :question_id");
            $params = array(
                ":question_id" => $question["id"]
            );
            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $responseA = null;
            if ($e[0] != "00000") {
                $responseA = array(
                    "status" => "error",
                    "message" => $e[2]
                );
                write_log("get_answers error: " . $e[2], $log_file_name);
            } else {
                $responseA = array(
                    "status" => "success",
                    "questions" => $question,
                    "answers" => $result
                );
                write_log("get_answers success: " . $n["trivia_id"], $log_file_name);
            }
        }*/
    }
}