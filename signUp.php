<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kazini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" href="./css/loginMain.css" type="text/css">
    <link rel="stylesheet" href="./css/signUp.css" type="text/css">

</head>
<body>
<div class="container" id="container1">
    <div>
        <h1 id="head"> K<i style="color: darkred" class="fas fa-apple-alt"></i>zini</h1>
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
    <form id="form" method="post" action="./php/register.php" >
        <div>
            <input type="text" class="form-control input" id="fName" minlength="2" placeholder="first name" onkeydown="" name="firstName" required autofocus>
            <p class="error" id="fNameErr"></p>
        </div>
        <div>
            <input type="text" class="form-control input" id="lName" minlength="2" placeholder="Last name" name="lastName" required>
            <p class="error" id="lNameErr"></p>
        </div>
        <div>
            <input type="email" class="form-control input" id="email" placeholder="Email" name="email" required>
            <p class="error" id="emailErr"></p>
        </div>
        <div>
            <input type="password" class="form-control input" id="pass" placeholder="Password" minlength="4" maxlength="8" name="password" required>
            <p class="error" id="passErr"></p>
        </div>
        <div>
            <input type="password" class="form-control input" id="cpass" placeholder="confirm Password" minlength="4" maxlength="8" name="confirmPassword" required>
            <p class="error" id="cpassErr"></p>
        </div>
        <div>
            <input class="btn" type="submit" id="submit" value="Sign Up">
        </div>
        <p id="signIn">Have an account <a href="index.php">sign in</a></p>
    </form>
</div>
</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script scr="./node_modules/jquery-validation/jquery.validate.min.js"></script>
<script src="./javascript/signUp.js"></script>
</html>