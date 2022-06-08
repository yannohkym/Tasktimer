<?php
if(session_status() == 1){
    session_start();
}



try {
    require_once '../php/config.php';
   if(isset($_POST['note'])){
       $n = <<<SQL
        SELECT task_id FROM task_notification
        WHERE id = :id
    SQL;
       $m = $db->prepare($n);
       $m->bindValue('id', $_POST['id']);
       if (!$m->execute()) {
           throw new Exception($m->errorInfo()[2]);
       }
       $r = $m->fetchAll();
       $id = $r[0]['task_id'];
   } else{
       $id = $_POST['id'];
   }


if(isset($_POST['pomodoro'])){
    if (isset($_POST['config'])){
        $pom = <<<SQL
        SELECT  p.*, u.Pomodoro
        FROM users u
        INNER JOIN pomodoro p on u.Pomodoro = p.id
        WHERE u.id = :id
    SQL;
        $pomodoro = $db->prepare($pom);
        $pomodoro->bindValue("id", $_SESSION['id']);
        if (!$pomodoro->execute()) {
            throw new Exception($pomodoro->errorInfo()[2]);
        }
        $results = $pomodoro->fetchAll();
        echo json_encode($results);
    }else{
        $pom = <<<SQL
        SELECT  pomodoro
        FROM task
        WHERE id = :id
SQL;
        $pomodoro = $db->prepare($pom);
        $pomodoro->bindValue("id", $_SESSION['pomId']);
        $pomodoro->execute();
        $results = $pomodoro->fetchAll();

        echo json_encode($results);
    }

}else{
    $q = <<<SQL
        SELECT c.name as category, c.id,t2.team_name,t.user_id,m.user_id AS member,task_name, t.pomodoro, t.isComplete,t.due_date, project_name,t.description,
               p.project_id,CONCAT(u.firstname, ' ', u.surname) AS name FROM task as t
        INNER JOIN task_Membership as m ON t.id = m.task_id 
        INNER JOIN users u on m.user_id = u.id 
        INNER JOIN  project p ON t.project_id = p.project_id
        INNER JOIN categories c on t.category = c.id
        INNER JOIN team t2 on p.team_id = t2.team_id
        WHERE t.id = :taskId
      SQL;
    $s = $db->prepare($q);
    $s->bindValue("taskId", $_POST['id']);
    $s->execute();
    $results = $s->fetchAll();

    $q1 = <<<SQL
        SELECT CONCAT(u.firstname, ' ', u.surname) AS name FROM task as t
        INNER JOIN users u on t.user_id = u.id 
        WHERE t.id = :taskId
      SQL;
    $s1 = $db->prepare($q1);
    $s1->bindValue("taskId", $_POST['id']);
    $s1->execute();
    $results1 = $s1->fetchAll();

    if($results[0]['user_id'] === $_SESSION['id']){
        $results[0]['user'] = true;
    }else{
        $results[0]['user'] = false;
    }
    $results[0]['assigner'] = $results1[0]['name'];
    echo json_encode($results);
}

}catch (Exception $e){
    echo json_encode($e);
}