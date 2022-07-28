<?php
session_start(); //Starting a new sesion
require_once(__DIR__ . "/db.php");
require_once(__DIR__ . "/../vendor/autoload.php");

function set_sess_var($sess_name, $db_var)
{
    $_SESSION[$sess_name] = $db_var;
}

function remove_sess_var($sess_name)
{
    unset($_SESSION[$sess_name]);
}

function get_user_id()
{
    if (isset($_SESSION["id"])) {
        return $_SESSION["id"];
    }
}

function get_user_fullname()
{
    if (isset($_SESSION["fname"]) && isset($_SESSION["lname"])) {
        return $_SESSION["fname"] . " " . $_SESSION["lname"];
    }
}

function get_username()
{
    if (isset($_SESSION["username"])) {
        return $_SESSION["username"];
    }
}

function get_email()
{
    if (isset($_SESSION["email"])) {
        return $_SESSION["email"];
    }
}

function get_birthday()
{
    if (isset($_SESSION["bday"])) {
        return $_SESSION["bday"];
    }
}

function has_role($checkrole)
{
    if (isset($_SESSION["roles"])) {
        foreach ($_SESSION["roles"] as $role) {
            foreach ($role as $key => $value) {
                if ($key == "name" && $value == $checkrole) {
                    return true;
                }
            }
        }
    }
}

function get_age()
{
    $bday = get_birthday();
    $today = date("Y-m-d");
    $diff = date_diff(date_create($bday), date_create($today));
    return $diff->format('%y');
}

function is_logged_in()
{
    if (isset($_SESSION["id"]) && isset($_SESSION["username"]) && isset($_SESSION["email"])) {
        return true;
    } else {
        return false;
    }
}

//write logs to file
function write_log($log_msg, $log_file)
{
    date_default_timezone_set('America/New_York');
    $timestamp = date("Y-m-d H:i:s");
    $log_dir = __DIR__ . "/../logs/";
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    $log_path = $log_dir . $log_file;
    $log = fopen($log_path, "a");
    fwrite($log, "[ " . $timestamp . " ] " . $log_msg . "\n");
    fclose($log);
}

function get_trivia_games()
{
    if (isset($_SESSION["trivia_games"])) {
        return $_SESSION["trivia_games"];
    }
}

function get_trivia_info()
{
    if (isset($_SESSION["trivia_info"])) {
        return $_SESSION["trivia_info"];
    }
}

function list_trivia_info()
{
    $q_id = null;
    if (isset($_SESSION["trivia_info"])) {
        $question_counter = 0;
        foreach ($_SESSION["trivia_info"] as $game) {

            if ($q_id != $game["question_id"]) {
                $q_id = $game["question_id"];
                $question_counter++;
            }

            echo "<h4>" . $question_counter . "</h4>";
            echo "<h2>" . $game["question"] . "</h2>";
            echo "<p>" . $game["answer"] . "</p>";
            if ($game["isCorrect"] == 1) {
                echo "<p class='correct'>Correct!</p>";
            } else {
                echo "<p class='incorrect'>Incorrect!</p>";
            }
            $q_id = $game["question_id"];
        }
    }
}

function error_msg($message)
{
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $msg->error($message);
}

function success_msg($message)
{
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $msg->success($message);
}

function warning_msg($message)
{
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $msg->warning($message);
}

function info_msg($message)
{
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $msg->info($message);
}

function show_flash_messages()
{
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $msg->display();
}
