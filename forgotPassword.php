<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kazini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" href="./css/loginMain.css" type="text/css">
</head>
<body>
<div class="container">
    <p style="font-size: large">Enter your email to set a new password</p>
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
    <form id="form" method="post" action="./php/resetPassword.php">
        <div>
            <input type="email" class="form-control input" id="email" placeholder="Email" name="email" required>
            <p class="error" id="emailErr"></p>
        </div>
        <div>
            <input class="btn" type="submit" id="submit" value="Send">
        </div>
        <p style="font-size: large">
            Go back to <a href="index.php">signIn</a> page
        </p>
    </form>
</div>
</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script scr="./node_modules/jquery-validation/jquery.validate.min.js"></script>
<script src="./javascript/signUp.js"></script>
</html>