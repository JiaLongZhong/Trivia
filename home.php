<!doctype html>
<html lang="en">
<?php
require_once(__DIR__ . "/lib/helpers.php");
if (!is_logged_in()) {
    die(header("Location: index.php"));
}
?>

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/homeheader.php"); ?>
</head>

<body>
    <?php show_flash_messages(); ?>
    <?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="intro" class="bg-image shadow-2-strong">
                        <div class="mask" style="background-color: rgba(0, 0, 0, 0.8);">
                            <div class="container d-flex align-items-center justify-content-center text-center h-100">
                                <div class="text-white">
                                    <h1 class="mb-3">IT490 Trivia Project</h1>
                                    <?php
                                    echo '<p>Welcome ' . get_user_fullname() . '</p>';
                                    ?>
                                    <a class="btn btn-outline-light btn-lg m-2" href="game.php" role="button" rel="nofollow" target="_blank">Play Trivia Game</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="quiz_container">
        <div id="games">
            <?php
            require_once(__DIR__ . "/rpc_producer.php");
            $trivia_rpc = new RpcClient();
            $response = json_decode($trivia_rpc->call(array("email" => get_email()), 'trivia_info_queue'), true);
            if ($response["status"] == "error") {
                error_msg("There was an error getting trivia games");
            } else {
                set_sess_var("trivia_info", $response["trivia_games_info"]);
            }
            $trivia_games = get_trivia_info();
            ?>
            <?php foreach ($trivia_games as $trivia_game) : ?>
                <!-- list games horizontally -->
                <div class="quiz_card">
                    <div class="quiz_card-body">
                        <h5 class="card-title"><?php echo $trivia_game["title"]; ?></h5>
                        <p class="card-text"><?php echo $trivia_game["description"]; ?></p>
                        <a href="triviagame.php?id=<?php echo $trivia_game["id"]; ?>" class="btn btn-primary">Play</a>
                    </div>
                </div>
            <?php
            endforeach; ?>
        </div>
    </div>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>

</html>