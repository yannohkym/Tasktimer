<?php
if(session_status() == 1){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task timer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" href="./node_modules/multiple-select/dist/multiple-select.min.css">
    <link rel="stylesheet" href="./dateTime/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="./css/homeMain.css">
    <link rel="stylesheet" type="text/css" href="./css/homePage.css">
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
    <p style="text-align: center"><i>"The end of labour is to gain leisure"<br> ~Aristotle</i></p>
    <div class="vsection">
        <p>Tasks Due soon</p><hr>
        <div id="table">
        <table class="table table-hover table-sm">
            <thead class="thead">
            <tr>
                <th scope="col"></th>
                <th scope="col">Task Details</th>
                <th scope="col">Pomodoro</th>
                <th scope="col">Due</th>
                <th scope="col">Project</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <?php
            try {
                require_once './php/config.php';
                $date = date("Y-m-d H:i:s",
                    mktime(0,0,0,
                        date('m'),date('d')+5,date('Y')));
                $today = date("Y-m-d H:i:s",
                    mktime(0,0,0,
                        date('m'),date('d'),date('Y')));
                $q = <<<SQL
                    SELECT t.id,task_name, pomodoro, t.due_date, project_name FROM task as t
                    INNER JOIN task_Membership as m ON t.id = m.task_id 
                    INNER JOIN  project p ON t.project_id = p.project_id
                    WHERE m.user_id = :userId AND t.isComplete = false
                    AND t.due_date <= :date AND t.due_date > :today;
                  SQL;
                $s = $db->prepare($q);
                $s->bindValue("userId", $_SESSION['id']);
                $s->bindValue("date", $date);
                $s->bindValue("today", $today);
                $s->execute();
                $results = $s->fetchAll();
                if(!empty($results)){
                    foreach ($results as $id){
                        echo '<tr class="task" id="'. $id['id'].'"><td class="check" id="'. $id['id'].'"><i style="color: gray" class="far fa-check-circle"></i></td>
                <td class="taskName" id="'. $id['id'].'">
                   '.$id['task_name'].'
                </td>
                <td>
                   ';
                if($id['pomodoro']<=4) {
                    for($i=0; $i<(int)$id['pomodoro']; $i++){
                        echo '<i style="color: orangered" class="fas fa-apple-alt"></i>';
                    }
                }else{
                    echo $id['pomodoro'] . '<i style="color: orangered" class="fas fa-apple-alt"></i>';
                };
                echo '     
                </td>
                <td id="d'. $id['id'].'">';
                    $current = strtotime($today);
                    $date1 = strtotime($id['due_date']);

                    $time = new DateTime($id['due_date']);
                    $time1 = $time->format('h:i A');
                    $datediff = $date1 - $current;
                    $difference = floor($datediff/(60*60*24));
                    if($difference==0)
                    {
                        echo '<span style="color: red">today<br>'.$time1.'</span>';
                    }
                    else if($difference == 1)
                    {
                        echo '<span style="color: red">tomorrow<br>'.$time1.'</span>';
                    }
                    else
                    {
                        $dayOfWeek = date("l", $date1);
                        echo '<span style="color: green">' . $dayOfWeek . '<br>'.$time1.'</span>';
                    }
                    echo '
                </td>
                <td>
                '.$id['project_name'].'
                </td>
                <td class="vstat" id="'.$id['id'].'"><button id="startask">start</button></td>
            </tr>';
                    }
            echo '</tbody>
        </table>
        </div> ';
                }else{
                    echo  '</tbody>
        </table>
        </div> <p style="color: gray; text-align: center">oops! no task due in the next five days</p>';
                }

            } catch (Exception $e) {
                echo 'Error ' . $e->getMessage();
            }
            ?>
        </div>
        <div class="vsection">
            <p>Recent Projects</p><hr>
            <div id="displayP">
                <?php
                try{
                    require_once './php/config.php';
                    $query = <<<SQL
                        SELECT p.project_name, p.project_id, t.team_name
                        FROM project p 
                        INNER JOIN project_Membership pM on p.project_id = pM.project_id
                        INNER  JOIN team t on p.team_id = t.team_id
                        WHERE pM.user_id = :id
                        ORDER BY p.added_at DESC 
                        LIMIT 5
                SQL;
                    $state = $db->prepare($query);
                    $state->bindValue('id', $_SESSION['id']);
                    $state->execute();
                    $results = $state->fetchAll();

                    if (!empty($results)){
                        foreach ($results as $result){
                            echo '<div class="projDis" id="'.$result['project_id'].'">
                    <i class="fas fa-project-diagram x"></i>
                    <p class="projp"><span class="projN">'.$result['project_name'].'</span><br><span class="projT">'.$result['team_name'].'</span></p>
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



        <div id="toast"></div>
</div>

<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
<script src="./node_modules/moment/min/moment.min.js"></script>
<script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="./javascript/homePage.js"></script>
<script src="./javascript/homeMain.js"></script>
</body>
</html>