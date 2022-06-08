<?php
if(session_status() == 1){
    session_start();
}



try {
    require_once '../php/config.php';
    $q = <<<SQL
        SELECT c.name as category, c.id,t2.team_name,t.user_id,m.user_id AS member,task_name, t.pomodoro, t.isComplete,t.due_date, project_name,t.description,
               p.project_id,CONCAT(u.firstname, ' ', u.surname) AS name FROM task as t
        INNER JOIN task_Membership as m ON t.id = m.task_id 
        INNER JOIN  project p ON t.project_id = p.project_id
        INNER JOIN categories c on t.category = c.id
        INNER JOIN users u on m.user_id = u.id 
        INNER JOIN team t2 on p.team_id = t2.team_id
        WHERE t.id = :taskId
      SQL;
    $s = $db->prepare($q);
    $s->bindValue("taskId", 108);
    $s->execute();
    $results = $s->fetchAll();

    $q1 = <<<SQL
        SELECT CONCAT(u.firstname, ' ', u.surname) AS name FROM task as t
        INNER JOIN users u on t.user_id = u.id 
        WHERE t.id = :taskId
      SQL;
    $s1 = $db->prepare($q1);
    $s1->bindValue("taskId", 108);
    $s1->execute();
    $results1 = $s1->fetchAll();
    var_dump($results);
}catch (Exception $e){
    echo $e->getMessage();
}

