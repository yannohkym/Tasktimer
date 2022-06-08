<?php
if(session_status() == 1){
    session_start();
}

try {
    require_once '../php/config.php';

    if ($_POST['pomodoro'] == 1) {

        $db->beginTransaction();
        $pom = <<<SQL
        INSERT INTO pomodoro(duration, short_break, long_break, intervals)
        VALUES (:duration, :short, :long, :intervals)
        SQL;
        $pomodo = $db->prepare($pom);
        $values = [
            'duration' => $_POST['updatepom'][0],
            'short' => $_POST['updatepom'][1],
            'long' => $_POST['updatepom'][2],
            'interval' => $_POST['updatepom'][3]
        ];
        if (!$pomodo->execute($values)) {
            throw new Exception($pomodo->errorInfo()[2]);
            $db->rollback();
        }
        $pomId = $db->lastInsertId();

        $pom1 = <<<SQL
        UPDATE users SET Pomodoro = :pom WHERE id = :userId
    SQL;
        $pomodoro1 = $db->prepare($pom1);
        $pomodoro1->bindValue('pom', $pomId);
        $pomodoro1->bindValue('userId', $_SESSION['id']);
        if (!$pomodoro1->execute()) {
            throw new Exception($pomodoro1->errorInfo()[2]);
            $db->rollback();
        }
        $db->commit();

    } else {
        $pom = <<<SQL
        UPDATE pomodoro 
        SET duration = :duration, short_break = :short, long_break = :long,  intervals = :interval
        WHERE id = :id
    SQL;
        $pomodoro = $db->prepare($pom);
        $values = [
            'id' => $_POST['pomodoro'],
            'duration' => $_POST['updatepom'][0],
            'short' => $_POST['updatepom'][1],
            'long' => $_POST['updatepom'][2],
            'interval' => $_POST['updatepom'][3]
        ];
        if (!$pomodoro->execute($values)) {
            throw new Exception($pomodoro->errorInfo()[2]);
        }

    }
}catch (Exception $e){
    echo $e->getMessage();
}