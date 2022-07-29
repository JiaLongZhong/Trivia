<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <?php require_once(__DIR__ . "/partials/header.php"); ?>
</head>
<?php
require_once(__DIR__ . "/lib/helpers.php");
//remove session variables and destroy session
session_unset();
session_destroy();
success_msg("You have been logged out successfully!");
show_flash_messages();
header("refresh:3;url=login.php");
