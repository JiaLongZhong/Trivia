<?php require_once(__DIR__ . "/../lib/helpers.php"); ?>
<div class="container">

    <footer class="py-3 my-4">
        <?php if (!is_logged_in()) : ?>
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="index.php" class="nav-link px-2 text-muted">Home</a></li>
                <li class="nav-item"><a href="login.php" class="nav-link px-2 text-muted">Login</a></li>
                <li class="nav-item"><a href="register.php" class="nav-link px-2 text-muted">Sign Up</a></li>
            </ul>
        <?php endif; ?>
        <?php if (is_logged_in()) : ?>
            <h3 class="px-2 text-muted text-center">Trickster Trivia</h3>
        <?php endif; ?>
        <p1 class="text-center text-muted">&copy;2022 Copyright @JiaZhong @JavierArtiga @SmitJoshi @DominicQuitoni @EmilyHontiveros</p1>
    </footer>
</div>
