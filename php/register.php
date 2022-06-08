<?php
require_once 'sendMail.php';

try {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];

    require_once 'config.php';
    $query = <<<SQL
    SELECT id FROM users WHERE email = :email;
SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("email", $email);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (!empty($result)) {
        $msg = "Error! Email already registered";
        header("location: ../signUp.php?id=$msg");
    } else {;
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db->beginTransaction();

        $query = <<<SQL
            INSERT INTO users (firstname, surname, email, password) 
            VALUES (:firstName, :lastName, :email, :password)
        SQL;
        $stmt1 = $db->prepare($query);
        $params = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $hash
        ];
        if (!$stmt1->execute($params)) {
            throw new Exception($stmt1->errorInfo()[2]);
        }
        $userId = $db->lastInsertId();

        $query1 = <<<SQL
            INSERT INTO team (user_id, team_name, description, isDefault) 
            VALUES (:user, :teamName, :description, '1')
        SQL;
        $stmt2 = $db->prepare($query1);
        $params1 = [
            'user' => $userId,
            'teamName' => "MyTeam" . $userId,
            'description' => $firstName . " " . $lastName . " Default Team",
        ];
        if (!$stmt2->execute($params1)) {
            throw new Exception($stmt2->errorInfo()[2]);
        }
        $teamId = $db->lastInsertId();

        $query2 = <<<SQL
            INSERT INTO project (team_id, user_id, project_name, description, isDefault) 
            VALUES (:team, :user, :projectName, :description, '1')
        SQL;
        $stmt3 = $db->prepare($query2);
        $params2 = [
            'team' => $teamId,
            'user' => $userId,
            'projectName' => "MyProject" . $userId,
            'description' => $firstName . " " . $lastName . " Default Project"
        ];
        if (!$stmt3->execute($params2)) {
            throw new Exception($stmt3->errorInfo()[2]);
        }
        $projectId = $db->lastInsertId();

        $query1 = <<<SQL
        INSERT INTO project_Membership(project_id, user_id)
        VALUES (:projectId, :userId)
SQL;
        $statement1 = $db->prepare($query1);
        $statement1->bindValue('projectId', $projectId);
        $statement1->bindValue('userId', $userId);
            if (!$statement1->execute()) {
                throw new Exception($statement1->errorInfo());
            }


        $categories = ['To_Do', 'Doing', 'Done'];
        $q = <<<SQL
            INSERT INTO categories(name, project_id)
            VALUES (:name, :id)
        SQL;

        $s = $db->prepare($q);;
        $s->bindValue('id', $projectId);
        foreach ($categories as $category) {
            $s->bindValue('name', $category);
            if (!$s->execute()) {
                throw new Exception($s->errorInfo()[2]);
            }
        }
        $subject = "Tasktimer Email verification";
        $message = "<p>Thank you for signing up on our site. 
                        Please click on the link below to verify your account:.</p>
                    <a href='http://". $_SERVER ['HTTP_HOST'] ."/Tasktimer/php/verifyEmail.php?token=". $hash . "'>Verify Email!</a>";

        if(sendVerificationEmail($email, $subject, $message)) {
            $msg1 = "<p  style='color: green !important;'>user registered successfully, kindly check your email to verify your account</p>";
            header("location: ../index.php?id=$msg1");
            echo 'if statement';
        }else{
            $msg = "An Error occurred! check your Email and try again";
            header("location: ../signUp.php?id=$msg");
        };
        $db->commit();
    }
}catch (Exception $e) {
        $msg2 ='Server Error Occurred! try again'. $e->getMessage() ;
        header("location: ../signUp.php?id=$msg2");
        $db->rollBack();
    }

