<?php
if(session_status() == 1){
    session_start();
}
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasktimer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" href="./css/login.css" type="text/css">
    <link rel="stylesheet" href="./css/loginMain.css" type="text/css">

</head>
<body>
<div class="container">
    <div>
        <h1 id="head">
            T<i style="color: darkred" class="fas fa-apple-alt"></i>mer
        </h1>
    </div>
    <?php
    if(isset($_GET['id'])) { ?>
        <div id="err" class="alert alert-primary animate__animated animate__bounceIn" role="alert">
            <?php
            echo $_GET['id'];
            ?>
        </div>
        <?php
    }
    ?>
    <div id="alert1" class="alert alert-primary animate__animated animate__bounceIn" role="alert">
        Error while submitting!<br>
        kindly fill the form as required
    </div>
    <form id="form" method="post" action="./php/login.php" >
        <div>
            <input type="email" class="form-control input" id="email" placeholder="Email" name="email" required>
            <p class="error" id="emailErr"></p>
        </div>
        <div>
            <input type="password" class="form-control input" id="pass" placeholder="Password" minlength="4" maxlength="8" name="password" required>
            <p class="error" id="passErr"></p>
        </div>
        <div>
            <input class="btn" type="submit" id="submit" value="Sign In">
        </div>
        <div id="extra">
            <span id="signUp">No account <a href="signUp.php">sign up</a></span>
            <a href="forgotPassword.php" id="fPass">Forgot password?</a>
        </div>
    </form>
</div>
</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script scr="./node_modules/jquery-validation/jquery.validate.min.js"></script>
<script src="./javascript/signUp.js"></script>
</html>