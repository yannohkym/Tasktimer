<?php

try{
    require_once 'config.php';
    $token = $_GET['token'];
    $query = <<<SQL
    UPDATE users SET verified=1 WHERE password = :token 
SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("token", $token);
    $stmt->execute();
   var_dump($token);
    $msg = "<p  style='color: green !important;'>Email verified successfully, kindly login</p>";
    header("location: ../index.php?id=$msg");
}catch (Exception $e){
    echo $e->getMessage();
}
