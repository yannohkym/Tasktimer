<?php

$dsn = 'mysql:host=127.0.0.1;dbname=task';
$user = 'root';
$password = 'DB@local2022';

try{
    $db = new PDO(
        $dsn,
        $user,
        $password
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    throw $e;
}
