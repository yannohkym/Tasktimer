<?php
if(session_status() == 1){
    session_start();
}



if(isset($_POST['taskName'])){
    $task['name'] = $_POST['taskName'];
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
        $db->beginTransaction();
        $query = <<<SQL
        INSERT INTO task(project_id, user_id, task_name, description, pomodoro, due_date, category)
        VALUES (:projectId, :userId, :taskName, :description, :pomodoro, :dueDate, :category)
SQL;

        $statement = $db->prepare($query);

        $params = [
            'projectId' => $task['project'],
            'userId'    => $_SESSION['id'],
            'taskName'  => $task['name'],
            'description'=> $task['description'],
            'pomodoro'  => $task['pomodoro'],
            'dueDate'   => $task['date'],
            'category'  => $task['category']
        ];
        if(!$statement->execute($params)){
            throw new Exception($statement->errorInfo()[2]);
        }
        $taskId = $db->lastInsertId();

        $query1 = <<<SQL
        INSERT INTO task_Membership(task_id, user_id)
        VALUES (:taskId, :userId)
SQL;
        $statement1 = $db->prepare($query1);
        $statement1->bindValue('taskId', $taskId);
        $statement1->bindValue('userId', $task['assignee']);
        if(!$statement1->execute()){
            throw new Exception($statement1->errorInfo());
        }

        if($task['assignee'] !== $_SESSION['id']) {
            $message = $_SESSION['name'] . " allocated you a task";
            $query2 = <<<SQL
                INSERT INTO task_notification(dest_id, src_name, message, task_id)
                VALUES (:userId, :name, :message, :task)
            SQL;

            $statement2 = $db->prepare($query2);
            $statement2->bindValue('name', $_SESSION['name']);
            $statement2->bindValue('message', $message);
            $statement2->bindValue('task', $taskId);
            $statement2->bindValue('userId', $task['assignee']);
            if (!$statement2->execute()) {
                throw new Exception($statement2->errorInfo());
            }
        }

        $db->commit();

        $msg =  $task['name'] . ' added successfully';
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
<div class="add" id="add1" >
    <div class="clse">
        &times;
    </div>
    <form id="form" method="post" action="./reuseables/addtask.php" >
        <div>
            <input type="text" class="form-control input" id="tName" placeholder="Task name" name="taskName" required autofocus>
        </div>

        <div>
            <span>project:</span>
            <select class="form-control input" id="tProject" name="tProject" >
                <option value="<?php if(isset($_SESSION['projectId'])){echo $_SESSION['projectId'];}?>" selected><?php if(isset($_SESSION['id'])){echo 'MyProject'. $_SESSION['id'];}?></option>
                <?php
                if(isset($_SESSION['id'])) {
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
            <br>

            <span>category:</span>
            <select id="proCat" class="form-control input" name="category">
                <?php
                if(isset($_SESSION['id'])) {
                    try {
                        require_once './php/config.php';
                        $query = <<<SQL
                            SELECT name as category, id
                            FROM categories
                            WHERE project_id = :projectId  
                        SQL;
                        $stmt = $db->prepare($query);
                        $stmt->bindValue("projectId", $_SESSION['projectId']);
                        if (!$stmt->execute()) {
                            throw new Exception($stmt->errorInfo()[2]);
                        }
                        $results = $stmt->fetchAll();
                        foreach ($results as $result){
                            echo ' <option class="aOpt" value="'.$result['id'].'" >'.$result['category'].'</option>';
                        }

                    } catch (Exception $e) {
                        echo 'Error ' . $e->getMessage();
                    }
                }
                ?>
            </select>
            <span>Assigned to:</span>

            <select class="form-control input" id="tAssignee" name="tAssignee" required>
                <?php
                if (isset($_SESSION['email'])){
                    echo '<option value="' . $_SESSION['id'] .'"selected>' . $_SESSION['email'] . '</option>';
                }
                ?>

            </select>

        </div>

        <div>
            <textarea class="form-control input" id="tDescription" placeholder="description" name="tDescription" maxlength="100" rows="4" cols="50"></textarea>
        </div>

        <div>
            <input type="number" class="form-control input" id="pomodoro" placeholder="No. of pomodoros" name="pomodoro" min="1" value="1">
        </div>

        <div>
            <span>Due date:</span>
            <input type="text" class="form-control input date" id="tDate" name="tdueDate" required>
        </div>
        <div>
            <input class="btn submit" type="submit" id="submit" value="Create">
        </div>
    </form>
</div>
