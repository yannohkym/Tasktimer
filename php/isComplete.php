<?php
try {
    require_once './php/config.php';
    $query = <<<SQL
    UPDATE task SET isComplete = !isComplete WHERE id = :id 
    SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("id", $_POST['id']);
    $stmt->execute();
}catch (Exception $e){
    echo json_encode($e);
}
