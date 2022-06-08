<?php

if (isset($_POST['email'])){
    $email = $_POST['email'];
   try{
       require_once 'sendMail.php';
       require_once 'config.php';
       $query = <<<SQL
    SELECT password, verified FROM users WHERE email = :email;
SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("email", $email);
    if(!$stmt->execute()){
        throw new Exception($stmt->errorInfo()[2]);
    };
    $result = $stmt->fetchAll();

    if(empty($result)){
        $msg = "Invalid Email address";
        header("location: ../forgotPassword.php?id=$msg");
    }else if($result[0]["verified"] == 0) {
        $msg = "Email not verified";
        header("location: ../forgotPassword.php?id=$msg");
    }
    else{
        $subject = "Kazini Password Reset";
        $message = "<p>Kindly click the link below to reset your password: </p>
            <a href='http://" . $_SERVER ['HTTP_HOST'] . "/Kazini/reset.php?token=" . $result[0]["password"] . "'>Reset Password!</a>";
        if(sendVerificationEmail($email, $subject, $message)){
            $msg = "<p  style='color: green !important;'>Check your email to reset your password</p>";
            header("location: ../forgotPassword.php?id=$msg");
        }else{
            $msg = "an error occurred try again";
            header("location: ../forgotPassword.php?id=$msg");
        };

    }
   }catch(Exception $e){
       $msg ='Server Error Occurred! try again'; //. $e->getMessage() ;
       header("location: ../forgotPassword.php?id=$msg");
   }
}


