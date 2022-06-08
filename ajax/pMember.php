<?php
if(session_status() == 1){
    session_start();
}

if(isset($_POST['pId'])){
    $id=$_POST['pId'];
}else{
    $id=$_POST['id'];
}

try{
    require_once '../php/config.php';
    if(isset($_POST['update'])){
        $q = <<<SQL
        DELETE FROM project_Membership WHERE user_id = :id AND project_id = :pid
    SQL;
        $s = $db->prepare($q);
        $s->bindValue('id', $id);
        $s->bindValue('pid', $_POST['update']);
        if (!$s->execute()) {
            throw new Exception($s->errorInfo()[2]);
        }

        $message = $_SESSION['name'] . " removed you from a project";
        $query2 = <<<SQL
                INSERT INTO project_notification(dest_id, src_name, message, project_id)
                VALUES (:userId, :name, :message, :project)
            SQL;

        $statement2 = $db->prepare($query2);
        $statement2->bindValue('name', $_SESSION['name']);
        $statement2->bindValue('message', $message);
        $statement2->bindValue('project', $_POST['update']);
        $statement2->bindValue('userId', $id);
        if (!$statement2->execute()) {
            throw new Exception($statement2->errorInfo());
        }

    }elseif(isset($_POST['updateTeam'])){
        $q = <<<SQL
        DELETE FROM team_Membership WHERE user_id = :id AND team_id = :pid
    SQL;
        $s = $db->prepare($q);
        $s->bindValue('id', $id);
        $s->bindValue('pid', $_POST['updateTeam']);
        if (!$s->execute()) {
            throw new Exception($s->errorInfo()[2]);
        }

        $message = $_SESSION['name'] . " removed you from a team";
        $query2 = <<<SQL
                INSERT INTO team_notification(dest_id, src_name, message, team_id)
                VALUES (:userId, :name, :message, :project)
            SQL;

        $statement2 = $db->prepare($query2);
        $statement2->bindValue('name', $_SESSION['name']);
        $statement2->bindValue('message', $message);
        $statement2->bindValue('project', $_POST['updateTeam']);
        $statement2->bindValue('userId', $id);
        if (!$statement2->execute()) {
            throw new Exception($statement2->errorInfo());
        }
    }else{
        $query = <<<SQL
                SELECT user_id, email, CONCAT(u.firstname, ' ' , u.surname) as name
                FROM project_Membership as m
                INNER JOIN users as u ON m.user_id = u.id 
                WHERE m.project_id = :projectId  
    SQL;
        $stmt = $db->prepare($query);
        $stmt->bindValue("projectId", $id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }
        $results1 = $stmt->fetchAll();

        if(isset($_POST['pId'])) {
            foreach ($results1 as $k=>$result){
                if($result['user_id'] == $_SESSION['id']){
                    unset($results1[$k]);
                }
            }
        }

        $query1 = <<<SQL
        SELECT name as category, id
        FROM categories
        WHERE project_id = :projectId  
    SQL;
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue("projectId", $id);
        if (!$stmt1->execute()) {
            throw new Exception($stmt1->errorInfo()[2]);
        }
        $results2 = $stmt1->fetchAll();

        $results = array_merge($results1,$results2);



        echo json_encode($results);
    }

}catch (Exception $e){
    echo json_encode($e);
}



