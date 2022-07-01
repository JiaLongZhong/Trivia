<?php
require("config.inc");
$source = "bitcoin";
if(isset($argv[1])){
	//$argv[0] is name of script always
	$source = $argv[1];
}
if(isset($_GET["query"])){
	$source = $_GET["query"];
}
$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://newsapi.org/v2/everything?q=$source&apiKey=$api_key",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	//CURLOPT_POSTFIELDS => "apiKey=$api_key&newsSource=$source",
	CURLOPT_HTTPHEADER => array(
		"content-type: application/x-www-form-urlencoded",
		//"x-rapidapi-host: $rapid_api_host",
		//"x-rapidapi-key: $rapid_api_key"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	//echo $response;
	$r = json_encode($response);

	if(isset($_GET["browser"])){

		echo "<pre>" . var_export($r,true)  . "</pre>";
	}
	else{
		$response_array = json_decode($response, true);
	$questions = $response_array["results"];
	if(isset($questions) && $response_array["response_code"]==0){
		/*$mq_connection = new AMQPConnection($brokerhost, $brokerport, $brokeruser, $brokerpass, $vhost);
		$mq_channel = $mq_connection->channel();
		$mq_channel->queue_declare('testQueue', false, false, false, false);
		$mq_channel->exchange_declare('testExchange', 'direct', false, false, false);
		$mq_channel->queue_bind('testQueue', 'testExchange');
		foreach($questions as $question){
			$question_array = array(
				"question" => $question["question"],
				"correct_answer" => $question["correct_answer"],
				"incorrect_answers" => $question["incorrect_answers"],
				"category" => $question["category"],
				"difficulty" => $question["difficulty"]
			);
			$mq_channel->basic_publish(new AMQPMessage(json_encode($question_array)), 'testExchange');
		}
		$mq_channel->close();
		$mq_connection->close();*/

		require_once(__DIR__ . "/rpc_producer.php");
            $reg_rpc = new RpcClient();
			foreach($questions as $question){
				$question_array = array(
					"question" => $question["question"],
					"correct_answer" => $question["correct_answer"],
					"incorrect_answers" => $question["incorrect_answers"],
					"category" => $question["category"],
					"difficulty" => $question["difficulty"]
				);
			}
			$response = json_decode($reg_rpc->call($question_array, "testQueue"),true);
			if ($response["status"] == "success") {
                echo "Questions written Successful";
                //header("Location: login.php");
            } elseif (
                $response["status"] == "error"
            ) {
                echo "Error.";
            } else {
                echo "An error occurred during question encoding. Please try again";
            }
	}
	}
}
?>
