<?php
if (session_status() == 1) {
    session_start();
}

$check = $_POST['id'];
try {
    require_once '../php/config.php';
    $query = <<<SQL
    SELECT t.task_name as name, tn.id as id, tn.message, p.project_name as project, t2.team_name as team, tn.checked, tn.created_at
    FROM task_notification tn
    INNER JOIN task t on t.id = tn.task_id
    INNER JOIN project p on t.project_id = p.project_id
    INNER JOIN team t2 on p.team_id = t2.team_id
    WHERE tn.dest_id = :id AND t.user_id != :id AND tn.checked IN (:status, :status1);
SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("id", $_SESSION['id']);
    if($check == 0){
        $stmt->bindValue("status", 0);
        $stmt->bindValue("status1", 1);
    }else{
        $stmt->bindValue("status", 0);
        $stmt->bindValue("status1", 0);
    }
    $stmt->execute();
    $results1 = $stmt->fetchAll();

    $query1 = <<<SQL
    SELECT p.project_name as name, pn.id as id, pn.message, t.team_name as team, pn.checked, pn.created_at
    FROM project_notification pn
    INNER JOIN project p on p.project_id = pn.project_id
    INNER JOIN team t on p.team_id = t.team_id
    WHERE pn.dest_id = :id AND p.user_id != :id AND pn.checked IN (:status, :status1);
    SQL;
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue("id", $_SESSION['id']);
    if ($check == 0) {
        $stmt1->bindValue("status", 0);
        $stmt1->bindValue("status1", 1);
    } else {
        $stmt1->bindValue("status", 0);
        $stmt1->bindValue("status1", 0);
    }
    $stmt1->execute();
    $results2 = $stmt1->fetchAll();

    $query2 = <<<SQL
    SELECT t.team_name as name, tn.id as id, tn.message, tn.checked, tn.created_at
    FROM team_notification tn
    INNER JOIN team t on t.team_id = tn.team_id
    WHERE tn.dest_id = :id AND t.user_id != :id AND tn.checked IN (:status, :status1);
    SQL;
    $stmt2 = $db->prepare($query2);
    $stmt2->bindValue("id", $_SESSION['id']);
    if ($check == 0) {
        $stmt2->bindValue("status", 0);
        $stmt2->bindValue("status1", 1);
    } else {
        $stmt2->bindValue("status", 0);
        $stmt2->bindValue("status1", 0);
    }
    $stmt2->execute();
    $results3 = $stmt2->fetchAll();

    foreach ($results1 as $key=>$result){
        $results1[$key]['type'] = 0;
    }

    foreach ($results2 as $key=>$result){
        $results2[$key]['project'] = '';
        $results2[$key]['type'] = 1;
    }

    foreach ($results3 as $key=>$result){
        $results3[$key]['project'] = '';
        $results3[$key]['team'] = '';
        $results3[$key]['type'] = 2;
    }

    $results = array_merge($results1,$results2, $results3);
    $time = array_column($results, 'created_at');
    array_multisort($time, SORT_DESC, $results);
    echo json_encode($results);
}catch (Exception $e){
    echo json_encode($e);
}