<?php
if(session_status() == 1){
    session_start();
}
if(isset($_POST['id'])){
    try {
        require_once '../php/config.php';

        if(isset($_POST['note'])){
            $n = <<<SQL
        SELECT project_id FROM project_notification
        WHERE id = :id
    SQL;
            $m = $db->prepare($n);
            $m->bindValue('id', $_POST['id']);
            $m->execute();
            $r = $m->fetchAll();
            $id = $r[0]['project_id'];
        } else{
            $id = $_POST['id'];
        }

        $q = <<<SQL
    SELECT CONCAT(u.firstname, ' ', u.surname) as name, p.user_id , p.project_name, p.description, t.team_name, t.team_id
    FROM project p
    INNER JOIN users u on p.user_id = u.id
    INNER JOIN team t on p.team_id = t.team_id
    WHERE p.project_id = :id
SQL;
        $s = $db->prepare($q);
        $s->bindValue("id", $id);
        $s->execute();
        $results = $s->fetchAll();

        $q1 = <<<SQL
    SELECT CONCAT(u.firstname, ' ', u.surname) as member, u.id as userid
    FROM project_Membership pM
    INNER JOIN users u on pM.user_id = u.id
    WHERE pM.project_id = :id
SQL;
        $s1 = $db->prepare($q1);
        $s1->bindValue("id", $id);
        $s1->execute();
        $results1 = $s1->fetchAll();

        if($results[0]['user_id'] == $_SESSION['id']){
            $results[0]['admin'] = true;
        }else{
            $results[0]['admin'] = false;
        }

        $results[0]['members[]'] = $results1;
       // $data = http_build_query(array('project' => $results));
        $results[0]['link'] = './projects.php?proj=' .$id;


        echo json_encode($results);
    }catch (Exception $e){
        echo json_encode($e);
    }

}

