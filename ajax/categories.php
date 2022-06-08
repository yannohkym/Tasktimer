<?php
if(session_status() == 1){
    session_start();
}

try{
    require_once '../php/config.php';
    if(isset($_POST['update'])){
        $que = <<<SQL
       UPDATE categories SET name = :name WHERE id = :id
    SQL;
        $stat = $db->prepare($que);
        $stat->bindValue('id', $_POST['id']);
        $stat->bindValue('name', $_POST['update']);
        if (!$stat->execute()) {
            throw new Exception($stat->errorInfo()[2]);
        }

    }else{
        $query1 = <<<SQL
        SELECT name, id
        FROM categories 
       WHERE project_id = :id
    SQL;
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue('id', $_SESSION['proId']);
        if (!$stmt1->execute()) {
            throw new Exception($stmt1->errorInfo()[2]);
        }
        $results1 = $stmt1->fetchAll();


        echo json_encode($results1);
    }



}catch (Exception $e){
    echo json_encode($e);
}