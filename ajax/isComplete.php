<?php
if(session_status() == 1){
    session_start();
}
try {
    require_once '../php/config.php';
    $query = <<<SQL
    UPDATE task SET isComplete = !isComplete WHERE id = :id 
    SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("id", $_POST['id']);
    $stmt->execute();

    $query1 = <<<SQL
        SELECT isComplete FROM task WHERE id = :id1 
    SQL;
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue("id1", $_POST['id']);
    $stmt1->execute();
    $results = $stmt1->fetchAll();
    echo json_encode($results[0]['isComplete']);

}catch (Exception $e){
    echo json_encode($e);
}
