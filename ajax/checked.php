<?php
if (session_status() == 1) {
    session_start();
}
try {
    require_once '../php/config.php';
    $query = <<<SQL
        SELECT checked
        FROM task_notification tn
        INNER JOIN task t on tn.task_id = t.id
        WHERE checked = 0 AND dest_id = :id AND t.user_id != :id
    SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue('id', $_SESSION['id']);
    $stmt->execute();
    $results1 = $stmt->fetchAll();

    $query1 = <<<SQL
        SELECT checked
        FROM project_notification pn
        INNER JOIN project p on pn.project_id = p.project_id
        WHERE checked = 0 AND dest_id = :id AND p.user_id != :id
    SQL;
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue('id', $_SESSION['id']);
    $stmt1->execute();
    $results2 = $stmt1->fetchAll();

    $query2 = <<<SQL
        SELECT checked
        FROM team_notification tn
        INNER JOIN team t on tn.team_id = t.team_id
        WHERE checked = 0 AND dest_id = :id AND t.user_id != :id
    SQL;
    $stmt2 = $db->prepare($query2);
    $stmt2->bindValue('id', $_SESSION['id']);
    $stmt2->execute();
    $results3 = $stmt2->fetchAll();

    $results = array_merge($results1, $results2,$results3);

    echo json_encode($results);
}catch (Exception $e){
    echo json_encode($e);
}