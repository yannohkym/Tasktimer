<?php
if(session_status() == 1){
    session_start();
}
try{
    require_once './php/config.php';
    if(isset($_POST['teamName'])){
        $query = <<<SQL
        INSERT INTO team(team_name, description, user_id)
        VALUES (:teamName, :description, :userId)
SQL;

        $statement = $db->prepare($query);
        $statement->bindValue('teamName', $_POST['teamName']);
        $statement->bindValue('description', $_POST['tmDescription']);
        $statement->bindValue('userId', $_SESSION['id']);
        if(!$statement->execute()){
            throw new Exception($statement->errorInfo()[2]);
        }

        $msg =  $_POST['teamName'] . ' added successfully';
        $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
        header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
    }

}catch (Exception $e){
    $msg =  'An error occurred! try again'; //.$e->getMessage();
    $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
    header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
}

?>
<div class="add" id="add3" >
    <div class="clse">
        &times;
    </div>
    <form id="tform" method="post" action="" >
        <div>
            <input type="text" class="form-control input" id="tmName" placeholder="Team name" name="teamName" required>
        </div>

        <div>
            <textarea class="form-control input" id="tmDescription" placeholder="description" name="tmDescription" maxlength="100" rows="4" cols="50"></textarea>
        </div>
        <div>
            <input class="btn submit" type="submit" id="tsubmit" value="Create">
        </div>
    </form>
</div>
