<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>

<body>
    <?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <?php
    if (!is_logged_in()) {
        error_msg("You do not have permission to access this page");
        show_flash_messages();
        die(header("Location: index.php"));
    }
    ?>
    <?php

    
    ?>
    <div class="container">
        <div class = "row">
            <div class="col-md-12">
                <h1>Leaderboard</h1>
                
            </div>
        </div> 
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Score</th>
                            <th>Trivia title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once(__DIR__ . "/rpc_producer.php");
                        $leaderboard_rpc = new RpcClient();
                        $parm = array("user" => get_user_id());
                        $response = json_decode($leaderboard_rpc->call($parm, 'leaderboard_queue'), true);
                        if ($response["status"] == "success") {
                            $leaderboard = $response["leaderboard"];
                            foreach ($leaderboard as $user) {
                                echo "<tr>";
                                echo "<td>" . $user["fname"] . "</td>";
                                echo "<td>" . $user["score"] . "</td>";
                                echo "<td>" . $user["title"] . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>

</html>