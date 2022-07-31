<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>

<body>
    <?php require_once(__DIR__ . "/partials/nav.php"); ?>

    <div class="container">
        <?php
        require_once(__DIR__ . "/lib/helpers.php");
        ?>
        <?php show_flash_messages(); ?>
        <form id="login" class="form" method="POST">
            <h4>Log In Here</h4>
            <label for="user_email">Username</label>
            <input type="text" id="user_email" name="user_email" />
            <br>
            <label for="pass">Password</label>
            <input type="password" id="pass" name="pass" required />
            <br>
            <input type="submit" id="l_submit" name="submit" value="Log In" />
        </form>
        <h4>Don't have an account? <a href="register.php">Register Here</a></h4>

        <?php include_once(__DIR__ . "/partials/footer.php"); ?>

        <?php
        if (isset($_POST["submit"])) {
            $user_email = null;
            $pass = null;
            $_POST["type"] = $_POST["submit"];


            if (isset($_POST["user_email"])) {
                $user_email = $_POST["user_email"];
            }

            if (isset($_POST["pass"])) {
                $pass = $_POST["pass"];
            }
            $isValid = true;
            if (!isset($user_email) || !isset($pass)) {
                $isValid = false;
            }

            if ($isValid) {

                require_once(__DIR__ . "/rpc_producer.php");
                $login_rpc = new RpcClient();
                $response = json_decode($login_rpc->call($_POST, 'login_queue'), true);
                if ($response["status"] == "error") {
                    error_msg("Login failed. Please try again.");
                    header("Location: login.php");
                } else {
                    success_msg("Login successful");
                    set_sess_var("fname", $response["fname"]);
                    set_sess_var("lname", $response["lname"]);
                    set_sess_var("username", $response["username"]);
                    set_sess_var("email", $response["email"]);
                    set_sess_var("id", $response["id"]);
                    set_sess_var("bday", $response["bday"]);
                    set_sess_var("roles", $response["roles"]);
                    set_sess_var("trivia_games", $response["trivia_games"]);
                    header("Location:home.php");
                }
            }
        }
        ?>