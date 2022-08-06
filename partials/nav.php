<?php require_once(__DIR__ . "/../lib/helpers.php"); ?>

<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <a class="navbar-brand">
        <img src="Logo.png" alt="Logo" height="36">&nbsp Trivia
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
            <?php if (is_logged_in()) : ?>
                <!-- add a search box in the navigation -->
                <li class="nav-item">
                    <form class="form-inline my-2 my-lg-0" action="list_users.php" method="post">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query" id="query" autocomplete="off">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <?php if (is_logged_in()) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">User Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="friend_request.php">Friend Requests</a>
                </li>
                <?php if (has_role("trivia_creator")) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="create_trivia.php">Create Trivia</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role("admin")) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Admin Functions</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            <?php endif; ?>

            <?php if (!is_logged_in()) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>