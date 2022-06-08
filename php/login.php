<?php
if(session_status() == 1){
    session_start();
}

try{
    require_once 'config.php';

    $query = <<<SQL
    SELECT * FROM users WHERE email = :email;
SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("email", $_POST['email']);
    if(!$stmt->execute()){
        throw new Exception($stmt->errorInfo()[2]);
    };
    $result = $stmt->fetchAll();
    if(empty($result)){
        $msg = "Error! incorrect email";
        header("location: ../index.php?id=$msg");
    }else if($result[0]["verified"] == 0){
        $msg = "Kindly verify your email before you login";
        header("location: ../index.php?id=$msg");
    }
    else if(!(password_verify($_POST['password'], $result[0]["password"]))){
        $msg = "Incorrect password";
        header("location: ../index.php?id=$msg");
    }else{
        $query1 = <<<SQL
    SELECT project_id FROM project WHERE user_id = :id AND isDefault = 1
SQL;
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue("id", $result[0]["id"]);
        if(!$stmt1->execute()){
            throw new Exception($stmt1->errorInfo()[2]);
        };
        $result1 = $stmt1->fetchAll();

        $query2 = <<<SQL
    SELECT team_id FROM team WHERE user_id = :id1 AND isDefault = 1;
SQL;
        $stmt2 = $db->prepare($query2);
        $stmt2->bindValue("id1", $result[0]["id"]);
        if(!$stmt2->execute()){
            throw new Exception($stmt2->errorInfo()[2]);
        };
        $result2 = $stmt2->fetchAll();

        $_SESSION['name'] = $result[0]["firstname"] . " " . $result[0]["surname"];
        $_SESSION['email'] = $result[0]["email"];
        $_SESSION['picture'] = $result[0]["profile_picture"];
        $_SESSION['id'] = $result[0]["id"];
        $_SESSION['projectId'] = $result1[0]['project_id'];
        $_SESSION['teamId'] = $result2[0]['team_id'];
        header("location: ../homePage.php");

    }
}catch (Exception $e){
    $msg1 = " Server error occurred! " . $e->getMessage();
    header("location: ../index.php?id=$msg1");
}


