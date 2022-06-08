<?php
if(session_status() == 1){
    session_start();
}
if(isset($_GET['proj'])){
    $_SESSION['proId'] = $_GET['proj'];
}
try {
    require_once './php/config.php';
    if(isset($_POST['pAss'])) {
        $db->beginTransaction();
        $que = <<<SQL
            INSERT INTO project_Membership(project_id, user_id)
            VALUES (:projId, :userId)
        SQL;
        $stat = $db->prepare($que);
        $stat->bindValue('projId', $_SESSION['proId']);

        foreach ($_POST['pAss'] as $user) {
            $stat->bindValue('userId', $user);
            if (!$stat->execute()) {
                throw new Exception($stat->errorInfo()[2]);
            }
        }

        foreach ($_POST['pAss'] as $assignee) {
            if ($assignee !== $_SESSION['id']) {
                $message = $_SESSION['name'] . " added you to a project";
                $query2 = <<<SQL
                INSERT INTO project_notification(dest_id, src_name, message, project_id)
                VALUES (:userId, :name, :message, :project)
            SQL;

                $statement2 = $db->prepare($query2);
                $statement2->bindValue('name', $_SESSION['name']);
                $statement2->bindValue('message', $message);
                $statement2->bindValue('project', $_SESSION['proId']);
                $statement2->bindValue('userId', $assignee);
                if (!$statement2->execute()) {
                    throw new Exception($statement2->errorInfo());
                }
            }
        }
        $db->commit();

        $msg = 'Member(s) added successfully';
        $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
        header("location: " . $_SERVER['HTTP_REFERER'] . "?id=$msg&icon=$icon");
    }
    if(isset($_POST['categ'])){
        $que = <<<SQL
            INSERT INTO categories(project_id, name)
            VALUES (:projId, :name)
        SQL;
        $stat = $db->prepare($que);
        $stat->bindValue('projId', $_SESSION['proId']);
        $stat->bindValue('name', $_POST['categ']);
        if (!$stat->execute()) {
            throw new Exception($stat->errorInfo()[2]);
        }
        $msg = 'Category added successfully';
        $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
        header("location: " . $_SERVER['HTTP_REFERER'] . "?id=$msg&icon=$icon");
    }

}catch (Exception $e){
    $msg = 'an Error Occurred! try again';// . $e->getMessage();
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
    <link rel="stylesheet" type="text/css" href="./css/project.css">
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
    <div>
        <span id="projo">
            <p class="projp">
                <?php
                try {
                    require_once './php/config.php';

                    $q = <<<SQL
                SELECT p.project_name, p.description, t.team_name
                FROM project p
                INNER JOIN team t on p.team_id = t.team_id
                WHERE p.project_id = :id
            SQL;
                    $s = $db->prepare($q);
                    $s->bindValue("id", $_SESSION['proId']);
                    $s->execute();
                    $results = $s->fetchAll();

                    echo ' <span class="projN" id="'. $_SESSION['proId'].'">' . $results[0]['project_name'] .
                        '           </span><br> 
                              <span class="projT">' . $results[0]['team_name'] .
                        '            </span>';
                }catch (Exception $e){
                    echo $e->getMessage();
                }

                ?>
            </p>
            <button id="pmem">Members</button>
        </span>
        <div>
            <select id="tCategory">
                <option value="0" selected>Incomplete task</option>
                <option value="1" >completed tasks</option>
                <option value="2" >Assigned to others</option>
                <option value="3" >All tasks</option>
            </select>
        </div>
    </div>
    <hr>
    <div>
        <table class="table table-hover table-sm">
            <thead class="thead">
            <tr>
                <th scope="col" class="vCheck"></th>
                <th scope="col" class="vdet">Task Details</th>
                <th scope="col" class="vpom">Pomodoro</th>
                <th scope="col" class="vdue">Due</th>
                <th scope="col" class="vpro">Project</th>
                <th scope="col" class="vstat"></th>
            </tr>
            </thead></table>
    </div>
    <div id="section">
        <?php
        try{
            require_once './php/config.php';
            $query1 = <<<SQL
        SELECT name, id
        FROM categories 
       WHERE project_id = :id
    SQL;
            $stmt1 = $db->prepare($query1);
            $stmt1->bindValue('id', $_SESSION['proId']);
            $stmt1->execute();
            $results1 = $stmt1->fetchAll();
            foreach ($results1 as $result){
                echo '<div class="vsection">
        <p style="color: darkred" contenteditable="true" id="'.$result['id'].'">'.$result['name'].'</p><hr>
        <div id="rAdded">
            <table class="table table-hover table-sm">
                <tbody id="'.$result['name'].'">
                   
                </tbody>
            </table>
            <p id="'.$result['name'].'1" style="color: gray; text-align: center">oops! no task added recently</p>
        </div>
    </div>';
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }

        ?>

        <p class="Acat">
            <i class="fas fa-plus-circle"></i>
            <span>add Category</span>
        <form style="display: none" id="catform" method="post" action="./projects.php">
            <input type="text" class="form-control input" id="pCat" name="categ" required>
            <input class="btn sub" type="submit" id="catsub" value="Add">
        </form>
        </p>
    </div>

</div>
<div class="divmem">
    <div id="divmem">

    </div>
    <p class="Amem">
        <i class="fas fa-plus-circle"></i>
        <span>add Member</span>
    </p>
    <div style="width: 35vw; display: none" id="addmem">
        <form id="memform" method="post" action="./projects.php">
            <select class="form-control input" id="pAss" name="pAss[]" required>
            </select>
            <input class="btn sub" type="submit" id="projsub" value="Add">
        </form>
    </div>
</div>
<div id="vtoast"></div>
</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
<script src="./node_modules/moment/min/moment.min.js"></script>
<script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="node_modules/maxlength-contenteditable/dist/maxlength-contenteditable.js"></script>
<script src="./javascript/homeMain.js"></script>
<script src="./javascript/project.js" type="module"></script>
</html>