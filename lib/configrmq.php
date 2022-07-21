<?php
$creds = parse_ini_file(__DIR__ . "/configrmq.ini");
$brokerhost = $creds["brokerhost"];
$brokerport = $creds["brokerport"];
$brokeruser = $creds["brokeruser"];
$brokerpass = $creds["brokerpass"];
?>
