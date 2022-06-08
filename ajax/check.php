<?php
if (session_status() == 1) {
    session_start();
}
$type = $_POST['type'];
$id = $_POST['id'];
try {
    require_once '../php/config.php';
    if($type == 0){
        $query = <<<SQL
    UPDATE task_notification SET checked = 1 WHERE id = :id
SQL;
        $stmt = $db->prepare($query);
        $stmt->bindValue("id", $id);
        $stmt->execute();
    }elseif ($type == 1) {
        $query = <<<SQL
            UPDATE project_notification SET checked = 1 WHERE id = :id
    SQL;
        $stmt = $db->prepare($query);
        $stmt->bindValue("id", $id);
        $stmt->execute();
    }elseif ($type == 2) {
        $query = <<<SQL
            UPDATE team_notification SET checked = 1 WHERE id = :id
    SQL;
        $stmt = $db->prepare($query);
        $stmt->bindValue("id", $id);
        $stmt->execute();
    }

}catch (Exception $e){
    echo json_encode($e->getMessage());
}
