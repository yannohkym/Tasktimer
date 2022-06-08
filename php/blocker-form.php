<?php

include 'php/config.php';
$messages = array();
$error_messages = array();
$success_messages = array();
$user_id = $_SESSION['id'];
$blocked_urls = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   //validate url
    $site_name = $_POST['site_name'];
    $url = $_POST['url'];

    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
        $error_messages[] = "The URL you entered is invalid!";
    }else{
        //valid url save to db
        try {
            $query = <<<SQL
            INSERT INTO blocker (user_id, url, site_name) 
            VALUES (:user_id, :url, :site_name)
        SQL;
            $stmt = $db->prepare($query);
            $stmt->bindValue("user_id", (int)$user_id);
            $stmt->bindValue("url", $url);
            $stmt->bindValue("site_name", $site_name);
            if($stmt->execute()){
                $success_messages[] = "Url successfully added!";
            }else{
                $error_messages[] = "There was an error saving the url. Pleas try again later";
            }

            $url = null;
            $site_name = null;

        }catch (Exception $e){
            echo $e->getMessage();
        }


    }
}

try {
    $query = <<<SQL
            SELECT * FROM blocker WHERE user_id = :user_id
        SQL;
    $stmt = $db->prepare($query);
    $stmt->bindValue("user_id", (int)$user_id);
    if($stmt->execute()){
        $blocked_urls = $stmt->fetchAll();
        // var_dump($blocked_urls); die;
    }else{
        $error_messages[] = "There was an error fetching the blocked urls";
    }

}catch (Exception $e){
    echo $e->getMessage();
}

$messages['success_messages'] = $success_messages;

$messages['error_messages'] = $error_messages;

