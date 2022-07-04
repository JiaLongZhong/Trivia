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
                                    require_once(__DIR__ . "/lib/helpers.php");
                                    echo '<p>Welcome ' . get_user_fullname() . '</p>';
                                    ?>
                                    <a class="btn btn-outline-light btn-lg m-2" href="#" role="button" rel="nofollow" target="_blank">Create Room</a>
                                    <a class="btn btn-outline-light btn-lg m-2" href="#" role="button" rel="nofollow" target="_blank">Join</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="logout.php">
                        <input type="submit" value="Log Out" />
                    </form>
                </div>
            </div>
        </div>
    </div>

        <?php include_once(__DIR__ . "/partials/footer.php"); ?>
    </body>

</html>
