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
    <?php
    if (isset($_POST)) {
        $query = null;
        $user_id = get_user_id();
        $user_name = get_username();
        if (isset($_POST["query"])) {
            $query = $_POST["query"];
        }
        $isValid = true;
        if ($query == null) {
            $isValid = false;
        }
        if ($isValid) {
            $search_array = array(
                "query" => $query,
                "user_id" => $user_id
            );
            require_once(__DIR__ . "/rpc_producer.php");
            $search_rpc = new RpcClient();
            $response = json_decode($search_rpc->call($search_array, 'friend_queue'), true);
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
        }
    }
    ?>
    <!-- heading -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Search for other players</h1>
            </div>
        </div>
    </div>
    <?php if (isset($results) && count($results) > 0) : ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>Results</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result) : ?>
                                <tr>
                                    <td><?php echo $result["username"]; ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="friend_id" value="<?php echo $result["id"]; ?>">
                                            <input type="submit" value="Add Friend" name="add_friend" class="btn btn-primary">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    if (isset($_POST["add_friend"])) {
        $friend_id = null;
        $user_id = get_user_id();
        if (isset($_POST["friend_id"])) {
            $friend_id = $_POST["friend_id"];
        }
        $isValid = true;
        if ($friend_id == null) {
            $isValid = false;
        }
        if ($isValid) {
            $add_friend_array = array(
                "user_id" => $user_id,
                "friend_id" => $friend_id
            );
            require_once(__DIR__ . "/rpc_producer.php");
            $add_friend_rpc = new RpcClient();
            $response = json_decode($add_friend_rpc->call($add_friend_array, 'friend_queue'), true);
            if ($response["status"] == "success") {
                success_msg("Friend request sent");
                show_flash_messages();
            } else {
                error_msg("Error sending friend request");
                show_flash_messages();
            }
        }
    }

    ?>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>

</html>