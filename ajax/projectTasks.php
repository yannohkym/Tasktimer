<?php
if (session_status() == 1) {
    session_start();
}

$check = $_POST['id'];
try {
    require_once '../php/config.php';
    if ($check == 3) {
        $query = <<<SQL
    SELECT t.id,task_name, pomodoro, t.due_date, t.added_at ,project_name, t.isComplete, t.category, t.project_id 
    FROM task as t
    INNER JOIN task_Membership as m ON t.id = m.task_id 
    INNER JOIN  project p ON t.project_id = p.project_id
    WHERE t.project_id = :id
    ORDER BY t.id DESC
  SQL;
        $s = $db->prepare($query);
        $s->bindValue("id", $_POST['project']);
        $s->execute();
        $results = $s->fetchAll();
        echo json_encode($results);
    }elseif ($check == 2){
        $query = <<<SQL
    SELECT t.id,task_name, pomodoro, t.due_date, t.added_at ,project_name, t.isComplete, t.category, t.project_id 
    FROM task as t
    INNER JOIN task_Membership as m ON t.id = m.task_id 
    INNER JOIN  project p ON t.project_id = p.project_id
    WHERE t.project_id = :id AND m.user_id != :userId
    ORDER BY t.id DESC
  SQL;
        $s = $db->prepare($query);
        $s->bindValue("id", $_POST['project']);
        $s->bindValue("userId", $_SESSION['id']);
        $s->execute();
        $results = $s->fetchAll();
        echo json_encode($results);
    }else{
        $query = <<<SQL
    SELECT t.id,task_name, pomodoro, t.due_date, t.added_at ,project_name, t.isComplete, t.category, t.project_id 
    FROM task as t
    INNER JOIN task_Membership as m ON t.id = m.task_id 
    INNER JOIN  project p ON t.project_id = p.project_id
    WHERE t.project_id = :id AND m.user_id = :userId AND t.isComplete = :status
    ORDER BY t.id DESC
  SQL;
        $s = $db->prepare($query);
        $s->bindValue("id", $_POST['project']);
        $s->bindValue("userId", $_SESSION['id']);
        $s->bindValue("status", $check);
        $s->execute();
        $results = $s->fetchAll();
        echo json_encode($results);
    }
}catch (Exception $e){
    echo json_encode($e);
}