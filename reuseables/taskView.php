<?php
if(session_status() == 1){
    session_start();
}

if(isset($_POST['taskName'])){
    $task['name'] = $_POST['taskName'];
    $task['id'] = $_POST['taskId'];
    if(isset($_POST['tProject'])){
        $task['project'] = $_POST['tProject'];
    }else{
        $task['project'] = $_SESSION['projectId'];
    }

    $task['assignee'] = $_POST['tAssignee'];
    $task['description'] = $_POST['tDescription'];
    $task['pomodoro'] = $_POST['pomodoro'];
    $task['date'] = $_POST['tdueDate'];
    $task['category'] = $_POST['category'];
    //var_dump($_POST);


    try{
        require_once '../php/config.php';

        $q= <<<SQL
            SELECT user_id FROM task_Membership WHERE task_id = :taskId
    SQL;
        $s=$db->prepare($q);
        $s->bindValue('taskId', $task['id']);
        $s->execute();
        $r = $s->fetchAll();


        $db->beginTransaction();

        $query = <<<SQL
        UPDATE `task` 
        SET project_id=:project, user_id=:user, task_name=:task,
          description=:description, pomodoro=:pomodoro, due_date=:date, category= :category
        WHERE id = :taskId
SQL;

        $statement = $db->prepare($query);
        $params = [
            'project' => $task['project'],
            'user'    => $_SESSION['id'],
            'task'  => $task['name'],
            'description'=> $task['description'],
            'pomodoro'  => $task['pomodoro'],
            'date'   => $task['date'],
            'taskId' => $task['id'],
            'category' => $task['category']
        ];
        if(!$statement->execute($params)){
            throw new Exception($statement->errorInfo()[2]);
        }

        if($r[0]['user_id'] !== $task['assignee']){
            $query1 = <<<SQL
           UPDATE task_Membership
           SET user_id=:userId 
           WHERE task_id = :taskId
        SQL;
            $statement1 = $db->prepare($query1);
            $statement1->bindValue('taskId', $task['id']);
            $statement1->bindValue('userId', $task['assignee']);
            if(!$statement1->execute()){
                throw new Exception($statement1->errorInfo());
            }
            $message = $_SESSION['name'] . " allocated you a task";
            $query2 = <<<SQL
            INSERT INTO task_notification(dest_id, src_name, message, task_id)
            VALUES (:userId, :name, :message, :task)
        SQL;
            $statement2 = $db->prepare($query2);
            $statement2->bindValue('name', $_SESSION['name']);
            $statement2->bindValue('message', $message);
            $statement2->bindValue('task', $task['id']);
            $statement2->bindValue('userId', $task['assignee']);
            if(!$statement2->execute()){
                throw new Exception($statement2->errorInfo());
            }

            $message1 = $_SESSION['name'] . " reallocated this task to someone else";
            $query3 = <<<SQL
            INSERT INTO task_notification(dest_id, src_name, message, task_id)
            VALUES (:userId, :name, :message, :task)
        SQL;
            $statement3 = $db->prepare($query3);
            $statement3->bindValue('name', $_SESSION['name']);
            $statement3->bindValue('message', $message1);
            $statement3->bindValue('task', $task['id']);
            $statement3->bindValue('userId', $r[0]['user_id']);
            if(!$statement3->execute()){
                throw new Exception($statement3->errorInfo());
            }
        }
        $db->commit();

        $msg =  $task['name'] . ' updated successfully';
        $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
        header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
        unset($task);
    }catch (Exception $e){
        $msg = 'an Error Occurred! try again';//. $e->getMessage();
        $icon = '<i style="color: red" id="check" class="fas fa-exclamation-circle"></i>';
        header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
        $db->rollBack();
    }
}
?>
<div class="add" id="view1" >
    <div class="clse" id="vclse">
        &times;
    </div>
    <div>
        <button id="complete"><i style="color: gray" class="far fa-check-circle"></i>Mark as complete</button>
    </div>
    <form id="vform" method="post" action="./reuseables/taskView.php" >
        <input style="display: none" type="text" class="form-control input" id="vtaskId" name="taskId" required autofocus>
        <div  style="display: flex">
            <input type="text" class="form-control input" id="vName" placeholder="Task name" name="taskName" required autofocus>
            <span>Due:</span>
            <input type="text" class="form-control input date" id="vDate" name="tdueDate" required>
        </div>

        <div id="vAssign">
            <label>Assigned to:
            <select class="form-control input" id="vAssignee" name="tAssignee" required>
                <?php
                if (isset($_SESSION['email'])){
                    echo '<option class="aOpt" value="' . $_SESSION['id'] .'"selected>' . $_SESSION['email'] . '</option>';
                }
                ?>
            </select>
            </label>
            <label style="display: none" id="By">By:
                <input type="text" class="form-control input" id="vAssigner" name="Assigner" required>
            </label>
            <div id="projoview">
                <label><span id="vIn">In:</span>
                    <select class="form-control input" id="vProject" name="tProject" >
                        <option id="select" value="" selected></option>
                        <?php
                        if(isset($_SESSION)) {
                            try {
                                require_once './php/config.php';
                                $query = <<<SQL
                                    SELECT t.team_name,project_id, project_name FROM project p
                                    INNER JOIN team t on p.team_id = t.team_id
                                    WHERE p.user_id = :userId AND p.isDefault = 0
                                SQL;
                                $stmt = $db->prepare($query);
                                $stmt->bindValue("userId", $_SESSION['id']);
                                $stmt->execute();
                                $results = $stmt->fetchAll();
                                foreach ($results as $id) {
                                    echo '<option value="' . $id['project_id'] . '">' . $id['team_name'] . ' - ' . $id['project_name'] . '</option>';
                                }
                            } catch (Exception $e) {
                                echo 'Error ' . $e->getMessage();
                            }
                        }
                        ?>
                    </select>
                </label>
                <label>Category:
                    <select class="form-control input" id="vCategory" name="category" required>
                    </select>
                </label>
            </div>
        </div>

        <div>
            <label>Description</label>
            <textarea class="form-control input" id="vDescription" placeholder="description" name="tDescription" maxlength="100" rows="4" cols="50"></textarea>
        </div>

        <div>
            <label>Pomodoro</label>
            <input type="number" class="form-control input" id="vpomodoro" placeholder="No. of pomodoros" name="pomodoro" min="1" value="1">
        </div>
        <div>
            <input style="display: none" type="submit" class="vedit" id="vsubmit" value="Update">
        </div>
    </form>
    <div>
        <button id="vedit" class="vedit"><span><i class="fas fa-edit"></i>edit</span></button>
    </div>
</div>
