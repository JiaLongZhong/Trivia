<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>
<script src="/static/js/updateProfile.js"></script>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php if (!is_logged_in()) {
    error_msg("You must be logged in to access this page.");
    show_flash_messages();
    die();
} ?>

<body>
    <!-- Friend Requests Heading-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Friend Requests</h1>
            </div>
        </div>
    </div>
    <?php
    require_once(__DIR__ . "/rpc_producer.php");
    $friend_rpc = new RpcClient();
    $response = json_decode($friend_rpc->call(array("user_id" => get_user_id()), 'friend_queue'), true);
    if ($response["status"] == "success") {
        $results = $response["result"];
        if (count($results) == 0) {
            info_msg("No results found");
        } else {
            success_msg(count($results) . " Result(s) found");
        }
        show_flash_messages();
    } else {
        error_msg("Error searching");
        show_flash_messages();
    }

    ?>
    <!-- Friend Requests List -->
    <!-- username, accept and decline button -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Accept</th>
                            <th>Decline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($results) && count($results) > 0) : ?>
                            <?php foreach ($results as $result) : ?>
                                <tr>
                                    <td><?php echo $result["username"]; ?></td>
                                    <td><button class="btn btn-success" onclick="acceptFriend(<?php echo $result["user_id"]; ?>)">Accept</button></td>
                                    <td><button class="btn btn-danger" onclick="declineFriend(<?php echo $result["user_id"]; ?>)">Decline</button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>

</html>