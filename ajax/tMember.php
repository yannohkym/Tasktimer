<?php
if(session_status() == 1){
    session_start();
}
if(isset($_POST['pId'])){
    try{
        require_once '../php/config.php';
        $query = <<<SQL
            SELECT user_id, email FROM team_Membership as m
            INNER JOIN users as u ON m.user_id = u.id 
            WHERE m.team_id = :teamId AND m.user_id != :id  
    SQL;
        $stmt = $db->prepare($query);
        $stmt->bindValue("teamId", $_POST['pId']);
        $stmt->bindValue("id", $_SESSION['id']);
        $stmt->execute();
        $results = $stmt->fetchAll();

    if(isset($_POST['projid'])){
        $que = <<<SQL
            SELECT user_id
            FROM project_Membership as m
            WHERE m.project_id = :projectId  
        SQL;
        $st = $db->prepare($que);
        $st->bindValue("projectId", $_POST['projid']);
        if (!$st->execute()) {
            throw new Exception($st->errorInfo()[2]);
        }
        $results1 = $st->fetchAll();
        foreach ($results1 as $pmem){
            foreach ($results as $k=>$tmem){
                if($pmem['user_id'] == $tmem['user_id']){
                    unset($results[$k]);
                }
            }
        }
        echo json_encode($results);
    }else{
        echo json_encode($results);
    }



    }catch (Exception $e){
        echo json_encode($e);
    }
}
