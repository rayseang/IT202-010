<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<!-- jQuery and JS bundle w/ Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>

         <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Logout</a></li>
            <li class="nav-item"><a class="nav-link" href="Checkout">Checkout</a></li>
        <?php endif; ?>
    </ul>
    <?php if (is_logged_in(false)): ?>
        <span class= "navbar-text">
                Points: <? php echo get_points_balance();?>
        </span>
    <?php endif;?>
</nav>
