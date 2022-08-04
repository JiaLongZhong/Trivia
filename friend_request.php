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
                                <?php if ($result["status"] == 0) : ?>
                                    <tr>
                                        <td><?php echo $result["username"]; ?></td>
                                        <form method="post">
                                            <input type="hidden" name="friend_id" value="<?php echo $result["sender_id"]; ?>">
                                            <td><input class="btn btn-success" type="submit" value="Accept" name="accept" /></td>
                                            <td><input class="btn btn-danger friend_reject" type="submit" value="Decline" name="decline" /></td>
                                        </form>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($result["status"] == 1) : ?>
                                    <tr>
                                        <td><?php echo $result["username"]; ?></td>
                                        <td>Accepted</td>
                                        <td></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($result["status"] == 2) : ?>
                                    <tr>
                                        <td><?php echo $result["username"]; ?></td>
                                        <td></td>
                                        <td>Declined</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST["accept"])) {
        $friend_rpc = new RpcClient();
        $response = json_decode($friend_rpc->call(
            array(
                "user_id" => get_user_id(),
                "friend_id" => $_POST["friend_id"],
                "action" => "1"
            ),
            'friend_queue'
        ), true);
        if ($response["status"] == "success") {
            success_msg("Friend request accepted");
            show_flash_messages();
        } else {
            error_msg("Error accepting friend request");
            show_flash_messages();
        }
    } else if (isset($_POST["decline"])) {
        $friend_rpc = new RpcClient();
        $response = json_decode(
            $friend_rpc->call(
                array(
                    "user_id" => get_user_id(),
                    "friend_id" => $_POST["friend_id"],
                    "action" => "2"
                ),
                'friend_queue'
            ),
            true
        );
        if ($response["status"] == "success") {
            success_msg("Friend request declined");
            show_flash_messages();
        } else {
            error_msg("Error declining friend request");
            show_flash_messages();
        }
    }

    ?>


    <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>

</html>