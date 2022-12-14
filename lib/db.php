<?php
//Turn on error detection
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function getDB()
{
    global $db;
    if (!isset($db)) {
        try {

            //require_once(__DIR__ . "/config.ini");
            $creds = parse_ini_file(__DIR__ . "/config.ini");
            $dbhost = $creds["dbhost"];
            $dbdatabase = $creds["dbdatabase"];
            $dbuser = $creds["dbuser"];
            $dbpass = $creds["dbpass"];


            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string, $dbuser, $dbpass);
        } catch (Exception $e) {
            var_export($e);
            $db = null;
        }
    }
    return $db;
}
