<?php

$token = $_GET['token'];
if(isset($_POST['password'])){
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

   try {
       require_once './php/config.php';
       $query = <<<SQL
    UPDATE users SET password = '$hash' WHERE password = :token
SQL;
       $stmt = $db->prepare($query);
       $stmt->bindValue("token", $token);
       if (!$stmt->execute()) {
           throw new Exception($stmt->errorCode()[2]);
       }
       $msg = "<p  style='color: green !important;'>password changed successfully, kindly login</p>";
       header("location: ./index.php?id=$msg");
   }catch (Exception $e){
       $msg = 'server Error Occurred! ' . $e->getMessage();
       header("location: ./reset.php?id=$msg");
   }

}

?>
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
    <p style="font-size: large">Enter your new password</p>
    <?php
    if(isset($_GET["id"])) { ?>
     <div id="err" class="alert alert-primary animate__animated animate__bounceIn" role="alert">
        <?php echo $_GET["id"] ?>
    </div>
    <?php
    }
        ?>
   <?php
    $form = '<form id="form" method="post" action="./reset.php?token=' . $token .'">
  
        <div>
            <input type="password" class="form-control input" id="pass" placeholder="Password" minlength="4" maxlength="8" name="password" required>
            <p class="error" id="passErr"></p>
        </div>
        <div>
            <input type="password" class="form-control input" id="cpass" placeholder="confirm Password" minlength="4" maxlength="8" name="confirmPassword" required>
            <p class="error" id="cpassErr"></p>
        </div>
        <div>
            <input class="btn" type="submit" id="submit" value="Send">
        </div>
        <p style="font-size: large">
            Go back to <a href="index.php">signIn</a> page
        </p>
    </form>
    </div>';
    echo $form;
    ?>

</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script scr="./node_modules/jquery-validation/jquery.validate.min.js"></script>
<script src="./javascript/signUp.js"></script>
</html>