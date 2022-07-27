<?php 
//api consumer to pull from api and insert into db over question_queue

require_once(__DIR__ . '/lib/configrmq.php');
require_once(__DIR__ . "/lib/helpers.php");
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection($brokerhost, $brokerport, $brokeruser, $brokerpass);
$channel = $connection->channel();

$channel->queue_declare('api_queue', false, false, false, false);

echo " [x] Awaiting RPC requests\n";
function api_call($n)
{
    $user_id = $n["userid"];
    $trivia_id = null;
    $log_file_name = "api_consumer.log";
    write_log("api_call from: " . $n['admin_email'], $log_file_name);
    echo "[xx] API call made\n";
    echo "[xx] Creating new trivia\n";
    $trivia_games = array();
    $db = getDB();
    $query = "INSERT INTO Trivia (title, description, visibility, user_id) VALUES (:title, :description, :visibility, :user_id)";
    $stmt = $db->prepare($query);
    $random_name = uniqid();
    $params = array(
        "title" => "Trivia " . $random_name,
        "description" => "This is a trivia game with " . $n['number_of_questions'] . " questions",
        "visibility" => 0,
        "user_id" => $n['userid']
    );
    $r = $stmt->execute($params);
    $e = $stmt->errorInfo();
    $trivia_id = $db->lastInsertId();
    if ($e[0] == "00000") {
        $query = "SELECT * FROM Trivia WHERE user_id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute(array(":id" => $n['userid']));
        $trivia_games = $stmt->fetchAll(PDO::FETCH_ASSOC);
        write_log("Trivia created successfully", $log_file_name);
    } else {
        $response = array(
            "status" => "error",
            "message" => $e[2]
        );
        write_log("Trivia created error: " . $e[2], $log_file_name);
    }

    echo "[xx] Trivia created with id: " . $trivia_id . "\n";
    // send a curl request to the api url
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://opentdb.com/api.php?amount=" . $n["number_of_questions"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        //CURLOPT_POSTFIELDS => "apiKey=$api_key&newsSource=$source",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
        ),
    ));
    $api_response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $data = json_decode($api_response, true);
    $question_id = '';
    $check1 = false;
    $check2 = false;
    $check3 = false;
    // echo var_dump($data);
    foreach ($data["results"] as $result) {
        foreach ($result as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if ($key == "question") {
                echo "[xx] Question: " . $value . "\n";
                $query = "INSERT INTO Questions (question, trivia_id, user_id) VALUES (:question, :trivia_id, :user_id)";
                $stmt = $db->prepare($query);
                $question = $value;
                $params = array(
                    "question" => $question,
                    "trivia_id" => $trivia_id,
                    "user_id" => $n['userid']
                );
                $stmt->execute($params);
                $e = $stmt->errorInfo();
                if ($e[0] == "00000") {
                    $check1 = true;
                } else {
                    $check1 = false;
                    //echo var_dump($e);
                }
                $question_id = $db->lastInsertId();
            } else if ($key == "correct_answer") {
                $query = "INSERT INTO Answers (answer, isCorrect, question_id, user_id) VALUES (:answer, :isCorrect, :question_id, :user_id)";
                $stmt = $db->prepare($query);
                $answer = $value;
                $params = array(
                    "answer" => $answer,
                    "isCorrect" => 1,
                    "question_id" => $question_id,
                    "user_id" => $n['userid']
                );
                $stmt->execute($params);
                $e = $stmt->errorInfo();
                if ($e[0] == "00000") {
                    $check2 = true;
                } else {
                    $check2 = false;
                    //echo var_dump($e);
                }
            } else if ($key == "incorrect_answers") {
                $incorrect_answers = $value;
                foreach ($incorrect_answers as $incorrect_answer) {
                    $query = "INSERT INTO Answers (answer, isCorrect, question_id, user_id) VALUES (:answer, :isCorrect, :question_id, :user_id)";
                    $stmt = $db->prepare($query);
                    $params = array(
                        "answer" => $incorrect_answer,
                        "isCorrect" => 0,
                        "question_id" => $question_id,
                        "user_id" => $n['userid']
                    );
                    $stmt->execute($params);
                    $e = $stmt->errorInfo();
                    if ($e[0] == "00000") {
                        $check3 = true;
                    } else {
                        $check3 = false;
                        //echo var_dump($e);
                    }
                }
            } else {
                continue;
            }
        }
    }
    if ($check1 && $check2 && $check3) {
        $response = array(
            "status" => "success",
            "message" => "Trivia created successfully",
            "trivia_games" => $trivia_games
        );
        write_log("Trivia created successfully", $log_file_name);
    } else {
        $response = array(
            "status" => "error",
            "message" => "Trivia created error"
        );
        write_log("Trivia created error", $log_file_name);
    }
    return $response;
}


echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $n = $req->body;
    $consumed_data = json_decode($n, true);
    $return_msg = json_encode(api_call($consumed_data));
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
$channel->basic_consume('api_queue', '', false, false, false, false, $callback);
while (count($channel->callbacks) || $channel->is_consuming() || $channel->is_open()) {
    $channel->wait();
}
$channel->close();
$connection->close();
