<?php
if(session_status() == 1){
    session_start();
}

try {
    require_once '../php/config.php';
    $n = <<<SQL
        SELECT team_id FROM team_notification
        WHERE id = :id
    SQL;
    $m = $db->prepare($n);
    $m->bindValue('id', 21);
    $m->execute();
    $r = $m->fetchAll();
    $id = $r[0]['team_id'];

    $q = <<<SQL
    SELECT CONCAT(u.firstname, ' ', u.surname) as name, t.user_id , t.team_name, t.description
    FROM team t
    INNER JOIN users u on t.user_id = u.id
    WHERE t.team_id = :id
SQL;
    $s = $db->prepare($q);
    $s->bindValue("id", $id);
    $s->execute();
    $results = $s->fetchAll();

    $q1 = <<<SQL
    SELECT CONCAT(u.firstname, ' ', u.surname) as member, u.id as userid
    FROM team_Membership tM
    INNER JOIN users u on tM.user_id = u.id
    WHERE tM.team_id = :id
SQL;
    $s1 = $db->prepare($q1);
    $s1->bindValue("id", $id);
    $s1->execute();
    $results1 = $s1->fetchAll();

    $results[0]['members[]'] = $results1;
    $array = array("member"=>$results[0]['name'], "userid"=>$results[0]['user_id']);
    array_push($results[0]['members[]'], $array);


    echo json_encode($results);
}catch (Exception $e){
        echo json_encode($e);
}
