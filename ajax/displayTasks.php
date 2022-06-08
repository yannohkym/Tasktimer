<?php
if(session_status() == 1){
    session_start();
}

$check = $_POST['id'];
try{
    require_once '../php/config.php';
    if($check == 3){
        $query = <<<SQL
    SELECT t.id,task_name, pomodoro, t.due_date, t.added_at ,project_name, t.isComplete, t.category, t.project_id 
    FROM task as t
    INNER JOIN task_Membership as m ON t.id = m.task_id 
    INNER JOIN  project p ON t.project_id = p.project_id
    WHERE t.user_id = :userId AND m.user_id != :userId
    ORDER BY t.id DESC
  SQL;
        $s = $db->prepare($query);
        $s->bindValue("userId", $_SESSION['id']);
        $s->execute();
        $results = $s->fetchAll();
    }else{
        $query = <<<SQL
    SELECT t.id,task_name, pomodoro, t.due_date, t.added_at ,project_name, t.isComplete, t.category, t.project_id 
    FROM task as t
    INNER JOIN task_Membership as m ON t.id = m.task_id 
    INNER JOIN  project p ON t.project_id = p.project_id
    WHERE m.user_id = :userId AND t.isComplete IN (:status, :status1)
    ORDER BY t.id DESC
  SQL;
        $s = $db->prepare($query);
        $s->bindValue("userId", $_SESSION['id']);
        if($check == 0){
            $s->bindValue("status", 0);
            $s->bindValue("status1", 0);
        }elseif ($check == 1){
            $s->bindValue("status", 1);
            $s->bindValue("status1", 1);
        }else if($check == 2){
            $s->bindValue("status", 0);
            $s->bindValue("status1", 1);
        }
        $s->execute();
        $results = $s->fetchAll();
    }
    echo json_encode($results);
}catch (Exception $e){
    echo json_encode($e);
}
