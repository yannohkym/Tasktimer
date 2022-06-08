<?php
if(session_status() == 1){
    session_start();
}
if(isset($_SESSION['id'])) {
    try {
        require_once './php/config.php';
        $query = <<<SQL
        SELECT project_id, project_name FROM project
        WHERE user_id = :userId AND isDefault = 0 AND team_id = :id
    SQL;
        $stmt = $db->prepare($query);
        $stmt->bindValue("userId", $_SESSION['id']);
        $stmt->bindValue('id', $_POST['id']);
        $stmt->execute();
        $results = $stmt->fetchAll();

        echo json_encode($results);
    } catch (Exception $e) {
        echo 'Error ' . $e->getMessage();
    }
}

