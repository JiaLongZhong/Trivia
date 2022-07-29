<!doctype html>
<html lang="en">

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>

<body>

    <?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <?php show_flash_messages(); ?>

    <div class="container">

        <div id="r-form">
            <form method="post" id="reg-test">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="<?php if (!isset($_POST["fname"])) {
                                                                        echo '';
                                                                    } else {
                                                                        echo $_POST["fname"];
                                                                    } ?>" required />
                <br>
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="<?php if (!isset($_POST["lname"])) {
                                                                        echo '';
                                                                    } else {
                                                                        echo $_POST["lname"];
                                                                    } ?>" required />
                <br>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php if (!isset($_POST["username"])) {
                                                                            echo '';
                                                                        } else {
                                                                            echo $_POST["username"];
                                                                        } ?>" required />
                <br>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php if (!isset($_POST["email"])) {
                                                                        echo '';
                                                                    } else {
                                                                        echo $_POST["email"];
                                                                    } ?>" required />
                <br>
                <label for="bday">Birthday</label>
                <input type="date" id="bday" name="bday" value="<?php if (!isset($_POST["bday"])) {
                                                                    echo '';
                                                                } else {
                                                                    echo $_POST["bday"];
                                                                } ?>" required />
                <br>
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" required />
                <br>
                <label for="pass1">Confirm Password</label>
                <input type="password" id="pass1" name="pass1" required />
                <br>
                <input type="submit" id="r_submit" name="submit" value="Register" />
            </form>
        </div>
    </div>


    <?php include_once(__DIR__ . "/partials/footer.php"); ?>


    <?php

    require_once(__DIR__ . "/lib/helpers.php");
    if (isset($_POST["submit"])) {
        $fname = null;
        $lname = null;
        $email = null;
        $username = null;
        $bday = null;
        $is_active = 1;
        $password = null;
        $password1 = null;

        if (isset($_POST["fname"])) {
            $fname = $_POST["fname"];
        }

        if (isset($_POST["lname"])) {
            $lname = $_POST["lname"];
        }

        if (isset($_POST["email"])) {
            $email = $_POST["email"];
        }

        if (isset($_POST["username"])) {
            $username = $_POST["username"];
        }

        if (isset($_POST["bday"])) {
            $bday = $_POST["bday"];
        }

        if (isset($_POST["pass"])) {
            $password = $_POST["pass"];
        }

        if (isset($_POST["pass1"])) {
            $password1 = $_POST["pass1"];
        }

        $isValid = true;

        if (strlen($username) < 8 || strlen(($username) > 32)) {
            echo "Username must be between 8 and 32 characters";
            $isValid = false;
        }

        if (strlen($password) < 8) {
            echo "Password must be 8 characters or more";
            $isValid = false;
        }


        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/', $password)) {
            echo "Password must contain a capital letter, a number, and a special character";
            $isValid = false;
        }


        if ($password != $password1) {
            echo "Passwords don't match";
            $isValid = false;
        }


        if (!isset($email) || !isset($username) || !isset($fname) || !isset($bday) || !isset($password) || !isset($password)) {
            echo "Unexpected error";
            $isValid = false;
        }

        if ($isValid) {
            $pass_hash = password_hash($password, PASSWORD_BCRYPT);

            $registration_array = array(
                "fname" => $fname,
                "lname" => $lname,
                "email" => $email,
                "username" => $username,
                "bday" => $bday,
                "is_active" => $is_active,
                "pass" => $pass_hash,
                "type" => $_POST["submit"]
            );
            require_once(__DIR__ . "/rpc_producer.php");
            $reg_rpc = new RpcClient();
            $response = json_decode($reg_rpc->call($registration_array, 'reg_queue'), true);
            // echo var_dump($response);
            if ($response["status"] == "success") {
                success_msg("Registration Successful");
                header("Location: login.php");
            } elseif ($response["status"] == "error") {
                warning_msg("Account already exists. Please log in.");
                header("Location: login.php");
            } else {
                error_msg("An error occurred during registration. Please try again");
                header("Location: register.php");
            }
        }
    }


    ?>


</body>

</html>