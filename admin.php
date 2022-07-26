<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>

<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<body>
    <!-- Add Heading -->
    <h2 class="text-center">Get New Trivia Games</h2>
    <!-- Create a form with hidden data fields for username -->
    <form id="api-form" method="post" class="form">
        <input type="number" id="userid" name="userid" value="<?php echo get_user_id(); ?>" hidden readonly />
        <input type="text" id="admin_email" name="admin_email" value="<?php echo get_email(); ?>" hidden readonly />
        <input type="text" id="api_command" name="api_command" value="update-trivia" hidden readonly />
        <label for="number_of_games">Number of Games</label>
        <input type="number" id="number_of_questions" name="number_of_questions" max="50" min="1" step="1" />
        <input type="submit" id="api_submit" name="api_submit" value="Submit" />
    </form>
</body>
<?php
if (isset($_POST["api_submit"])) {
    $uid = null;
    $admin_username = null;
    $api_command = null;
    $number_of_questions = null;
    if (isset($_POST["userid"])) {
        $uid = $_POST["userid"];
    }
    if (isset($_POST["admin_email"])) {
        $admin_username = $_POST["admin_email"];
    }
    if (isset($_POST["api_command"])) {
        $api_command = $_POST["api_command"];
    }
    if (isset($_POST["number_of_questions"])) {
        $number_of_questions = $_POST["number_of_questions"];
    }
    $isValid = true;
    if ($uid == null) {
        $isValid = false;
    }
    if ($admin_username == null) {
        $isValid = false;
    }
    if ($api_command == null) {
        $isValid = false;
    }
    if ($number_of_questions == null || $number_of_questions < 1 || $number_of_questions > 50) {
        $isValid = false;
    }
    if ($isValid) {
        require_once(__DIR__ . "/rpc_producer.php");
        $update_rpc = new RpcClient();
        $response = json_decode($update_rpc->call($_POST, 'api_queue'), true);
        if ($response["status"] == "status") {
            set_sess_var("trivia_games", $response["trivia_games"]);
            echo "Update successful";
        } else {
            echo "Update unsuccessful\n";
            echo var_dump($response);
        }
    }
}

?>
<?php include_once(__DIR__ . "/partials/footer.php"); ?>