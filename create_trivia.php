<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>

<body>
    <?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <?php
    if (!has_role("trivia_creator")) {
        echo "You do not have permission to access this page";
        die();
    }
    ?>

    <div>
	<div class="row">
        <div class="col-12">
            <div class="card">
                <div id="intro" class="bg-image shadow-2-strong">
                    <div class="mask" style="background-color: rgba(0, 0, 0, 0.8);">
                        <div class="container d-flex align-items-center justify-content-center text-center h-100">
                            <div class="text-white">
                                <h1 class="mb-3">Create Trivia Questions </h1>
                                <h5 class="mb-4"><p>To be the best, you will have to beat the best!
                                      <br> Trivia creators can add questions and answers to the trivia games they created! </p>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <button id="create-trivia">Create Trivia</button>
        <form id="create-trivia-form" class="form" method="POST" style="display: none;">
            <label for="trivia-name">Name</label>
            <input type="text" id="trivia-name" name="trivia_name" />
            <br>
            <label for="description">Description</label>
            <input type="text" id="trivia-description" name="trivia_description" />
            <br>
            <input type="submit" id="trivia_submit" name="submit" value="Create" />
        </form>
    </div>
    <divc class="list">
        <?php list_created_games(); ?>
    </div>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>
<?php
if (isset($_POST["submit"])) {
    $trivia_name = null;
    $trivia_description = null;
    $_POST["type"] = $_POST["submit"];
    $_POST["id"] = get_user_id();
    $_POST["email"] = get_email();

    if (isset($_POST["trivia_name"])) {
        $trivia_name = $_POST["trivia_name"];
    }
    if (isset($_POST["trivia_description"])) {
        $trivia_description = $_POST["trivia_description"];
    }
    $isValid = true;
    if (!isset($trivia_name) || !isset($trivia_description)) {
        $isValid = false;
    }
    if ($isValid) {
        require_once(__DIR__ . "/rpc_producer.php");
        $trivia_rpc = new RpcClient();
        $response = json_decode($trivia_rpc->call($_POST, 'trivia_queue'), true);
        if ($response["status"] == "error") {
            echo "Trivia creation unsuccessful";
        } else {
            set_sess_var("trivia_games", $response["trivia_games"]);
            header("Location: home.php");
        }
    }
}

?>
<script src="static/js/toggleTriviaForm.js"></script>

</html>
