<?php
if(session_status() == 1){
    session_start();
}
if(isset($_GET['tid'])){
    $_SESSION['tid'] = $_GET['tid'];
}
try{
    require_once './php/config.php';
    if(isset($_POST['email'])){
        $query = <<<SQL
            SELECT id
            FROM users
            WHERE email = :email
        SQL;
        $statement = $db->prepare($query);
        $statement->bindValue('email', $_POST['email']);
        if(!$statement->execute()){
            throw new Exception($statement->errorInfo()[2]);
        }
        $results = $statement->fetchAll();

        $query1 = <<<SQL
            SELECT user_id
            FROM team_Membership
            WHERE team_id = :id
        SQL;
        $statement1 = $db->prepare($query1);
        $statement1->bindValue('id', $_SESSION['tid']);
        if(!$statement1->execute()){
            throw new Exception($statement1->errorInfo()[2]);
        }
        $results1 = $statement1->fetchAll();
        foreach ($results1 as $tmem){
            if($results[0]['id'] == $tmem['user_id']){
                $_SESSION['tError'] = 'User is already a team Member';
                unset($results[0]);
            }
        }
        if($results[0]['id'] == $_SESSION['id']){
            $_SESSION['tError'] = 'User is already a team Member';
            unset($results[0]);
        }



        if (empty($results)){
            if(!isset($_SESSION['tError'])){
                $_SESSION['tError'] = 'Error! Email not registered';
            }
        }else{
            $db->beginTransaction();
            $query = <<<SQL
            INSERT INTO team_Membership(team_id, user_id)
            VALUES (:team, :user)
        SQL;
            $statement = $db->prepare($query);
            $statement->bindValue('team', $_SESSION['tid']);
            $statement->bindValue('user', $results[0]['id']);
            if(!$statement->execute()){
                throw new Exception($statement->errorInfo()[2]);
            }
             $message = $_SESSION['name'] . " added you to a team";
             $query2 = <<<SQL
                INSERT INTO team_notification(dest_id, src_name, message, team_id)
                VALUES (:userId, :name, :message, :project)
            SQL;

                $statement2 = $db->prepare($query2);
                $statement2->bindValue('name', $_SESSION['name']);
                $statement2->bindValue('message', $message);
                $statement2->bindValue('project', $_SESSION['tid']);
                $statement2->bindValue('userId', $results[0]['id']);
                if (!$statement2->execute()) {
                    throw new Exception($statement2->errorInfo());
                }
                $db->commit();

            $msg = 'Member added successfully';
            $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
            header("location: " . $_SERVER['HTTP_REFERER'] . "?id=$msg&icon=$icon");
            }


        }

}catch (Exception $e){
    $msg = 'an Error Occurred! try again' . $e->getMessage();
    $icon = '<i style="color: red" id="check" class="fas fa-exclamation-circle"></i>';
    header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
    $db->rollBack();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kazini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" href="./node_modules/multiple-select/dist/multiple-select.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap4-toggle/css/bootstrap4-toggle.min.css">
    <link rel="stylesheet" href="./dateTime/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="./css/homeMain.css">
    <link rel="stylesheet" type="text/css" href="./css/team.css">
</head>
<script>
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
    }
</script>
<body>
<?php
require_once './reuseables/navbar.php';
require_once './reuseables/addtask.php';
require_once './reuseables/addproject.php';
require_once './reuseables/addteam.php';
require_once './reuseables/toast.php';
require_once './reuseables/taskView.php';
?>
    <div id="main">
        <?php
        try {
            require_once './php/config.php';
            $query = <<<SQL
            SELECT t.team_id ,t.team_name, t.description, u.id, CONCAT(u.firstname, ' ', u.surname) as name
            FROM team t
            INNER JOIN users u ON t.user_id = u.id 
            WHERE t.team_id = :id
    SQL;
            $state = $db->prepare($query);
            $state->bindValue('id', $_SESSION['tid']);
            $state->execute();
            $results = $state->fetchAll();
            echo '<div id="teams">
            <div id="teamName">
                <p class="teamN" id="'.$results[0]['team_id'].'">'.$results[0]['team_name'].'</p>
                <p class="teamDesc">'.$results[0]['description'].'</p>
            </div>
            <div id="members">
                <p style="text-align: center" class="label">Members</p><hr>
                <span class="teamMem" id="'.$results[0]['id'].'">'.$results[0]['name'].'</span><br>';

            $qu = <<<SQL
            SELECT u.id, CONCAT(u.firstname, ' ', u.surname) as name
            FROM team t
            INNER JOIN team_Membership tM on t.team_id = tM.team_id
            INNER JOIN users u on tM.user_id = u.id
            WHERE t.team_id = :id
    SQL;
            $st = $db->prepare($qu);
            $st->bindValue('id', $_SESSION['tid']);
            $st->execute();
            $r = $st->fetchAll();
            if (!empty($r)) {
                foreach ($r as $result) {
                    if($results[0]['id'] == $_SESSION['id']){
                        echo '<span class="teamMem">'.$result['name'].'</span>
                                <span id="'.$result['id'].'" class="trash">
                                    <i class="fas fa-trash-alt"></i> 
                                </span><br>';
                    }else{
                        echo '<span class="teamMem" id="'.$result['id'].'">'.$result['name'].'</span><br>';
                    }

                }
            }
             if($results[0]['id'] == $_SESSION['id']){
                 echo ' <p class="Amem">
            <i class="fas fa-plus-circle"></i>
            <span>add Member</span>
        </p>';
             }
             if(isset($_SESSION['tError'])){
                 echo '<div style="width: 35vw; display: block" id="addmem">
            <form id="memform" method="post" action="./teams.php">
                <div>
                    <input type="email" class="form-control input" id="email" placeholder="Email" name="email" required>
                    <p class="error" id="emailErr" style="display: block">'.$_SESSION['tError'].'</p>
                </div>
                <input class="btn sub" type="submit" id="projsub" value="Add">
            </form>
        </div>';
                 unset($_SESSION['tError']);
             }else{
                 echo '<div style="width: 35vw; display: none" id="addmem">
            <form id="memform" method="post" action="./teams.php">
                <div>
                    <input type="email" class="form-control input" id="email" placeholder="Email" name="email" required>
                    <p class="error" id="emailErr"></p>
                </div>
                <input class="btn sub" type="submit" id="projsub" value="Add">
            </form>
        </div>';
             }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        ?>


    </div>
            <div id="projects">
                <p class="label">Projects</p><hr>
                <div id="displayP">
                    <?php
                    try{
                        require_once './php/config.php';
                        $query = <<<SQL
                        SELECT p.project_name, p.project_id
                        FROM project p 
                        WHERE team_id = :id
                        ORDER BY p.added_at DESC 
                SQL;
                        $state = $db->prepare($query);
                        $state->bindValue('id', $_SESSION['tid']);
                        $state->execute();
                        $results = $state->fetchAll();

                        if (!empty($results)){
                            foreach ($results as $result){
                                echo '<div class="projDis" id="'.$result['project_id'].'">
                    <i class="fas fa-project-diagram x"></i>
                    <p class="projp"><span class="projN">'.$result['project_name'].'</span></p>
                </div>';
                            }
                        }else{
                            echo '<p style="color: gray; text-align: center">no project yet</p>';
                        }


                    }catch (Exception $e){
                        echo $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<div id="vtoast"></div>
</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
<script src="./node_modules/moment/min/moment.min.js"></script>
<script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="./javascript/homeMain.js"></script>
<script src="./javascript/teams.js"></script>
</html>