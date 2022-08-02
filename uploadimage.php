<html>

<head>

    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>

    <title> Upload Profile Picture </title>

    <style>
        input {
            width: 50%;
            height: 5%;
            border: 1px;
            border-radius: 05px;
            padding: 8px 15px 8px 15px;
            margin: 10px 0px 15px 0px;
            box-shadow: 1px 1px 2px 1px grey;
            font-weight: bold;
        }
    </style>

    </header>

<body>
    <center>
        <h1> Upload/Insert an Image </h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <label> Choose a Profile Picture: </label><br>
            <input type="file" name="image" id="image" /><br>

            <input type="submit" name="upload" value="Upload Image" /><br>

        </form>
    </center>
</body>

</html>

<?php

require_once(__DIR__ . "/lib/helpers.php");
if (!is_logged_in()) {
    die(header("Location: index.php"));
}

$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, 'trivia_app');

if (isset($_POST['upload'])) {
    $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
    $username = $_POST['username'];

    $query = "INSERT  INTO 'empimage'('image') VALUES ('$file') '";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo '<script type="text/javascript"> alert("Image Profile Uploaded")</script>';
    } else {
        echo '<script type="text/javascript"> alert("Image Profile Not Uploaded")</script>';
    }
}
