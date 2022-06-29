<!doctype html>
<html lang="en">
<?php require_once(__DIR__ . "/lib/helpers.php");
if (is_logged_in()) {
    header("Location: home.php");
}
?>

<head>
    <title><?php echo ucfirst(substr(basename(__FILE__), 0, -4)); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="static/css/index.css">
</head>

<body>
    <?php require_once(__DIR__ . "/partials/nav.php"); ?>
   <div class="row">
        <div class="col-12">
            <div class="card">
                <div id="intro" class="bg-image shadow-2-strong">
                    <div class="mask" style="background-color: rgba(0, 0, 0, 0.8);">
                        <div class="container d-flex align-items-center justify-content-center text-center h-100">
                            <div class="text-white">
                                <h1 class="mb-3">IT490 Trivia Project</h1>
                                <h5 class="mb-4"><p>To be the best, you will have to beat the best!
                                        Join the competitive environment of quizzes where players get to challenge the world leaderboard. Show off your glory by showcasing your achievement through hard-earned badges. Not in a competitive mood. No problem, users can create trivia and play with their family and friends. Join our wonderful Trivia community for the sake of glory and entertainment by logging in with your email. New to the platform, no problem, sign up today to join the community:</p>
                                </h5>
                                <a class="btn btn-outline-light btn-lg m-2" href="register.php" role="button" rel="nofollow" target="_blank">Sign Up</a>
                                <a class="btn btn-outline-light btn-lg m-2" href="login.php" role="button" rel="nofollow" target="_blank">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once(__DIR__ . "/partials/footer.php"); ?>
</body>

</html>
