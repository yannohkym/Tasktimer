<?php
if(session_status() == 1){
    session_start();
}



if(isset($_POST['projectName'])){
    $project['name'] = $_POST['projectName'];
    if(isset($_POST['team'])){
        $project['team'] = $_POST['team'];
    }else{
        $project['team'] = $_SESSION['teamId'];
    }

    $project['assignee[]'] = $_POST['pAssignee'];
    $project['description'] = $_POST['pDescription'];

    try{
        require_once '../php/config.php';
        $db->beginTransaction();
        $query = <<<SQL
        INSERT INTO project(team_id, user_id, project_name, description)
        VALUES (:teamId, :userId, :projName, :description)
SQL;

        $statement = $db->prepare($query);;
        $params = [
            'teamId' => $project['team'],
            'userId'    => $_SESSION['id'],
            'projName'  => $project['name'],
            'description'=> $project['description']
        ];
        if(!$statement->execute($params)){
            throw new Exception($statement->errorInfo()[2]);
        }
        $projectId = $db->lastInsertId();

        $query1 = <<<SQL
        INSERT INTO project_Membership(project_id, user_id)
        VALUES (:projectId, :userId)
SQL;
        $statement1 = $db->prepare($query1);
        $statement1->bindValue('projectId', $projectId);
        foreach ($project['assignee[]'] as $assignee) {
            $statement1->bindValue('userId', $assignee);
            if (!$statement1->execute()) {
                throw new Exception($statement1->errorInfo());
            }
        }

        foreach ($project['assignee[]'] as $assignee) {
            if ($assignee !== $_SESSION['id']) {
                $message = $_SESSION['name'] . " added you to a project";
                $query2 = <<<SQL
                INSERT INTO project_notification(dest_id, src_name, message, project_id)
                VALUES (:userId, :name, :message, :project)
            SQL;

                $statement2 = $db->prepare($query2);
                $statement2->bindValue('name', $_SESSION['name']);
                $statement2->bindValue('message', $message);
                $statement2->bindValue('project', $projectId);
                $statement2->bindValue('userId', $assignee);
                if (!$statement2->execute()) {
                    throw new Exception($statement2->errorInfo());
                }
            }
        }
        $categories = ['To_Do', 'Doing', 'Done'];
        $q = <<<SQL
            INSERT INTO categories(name, project_id)
            VALUES (:name, :id)
        SQL;

        $s = $db->prepare($q);;
        $s->bindValue('id', $projectId);
        foreach ($categories as $category){
            $s->bindValue('name', $category);
            if(!$s->execute()){
                throw new Exception($statement->errorInfo()[2]);
            }
        }

        $db->commit();

        $msg =  $project['name'] . ' added successfully';
        $icon = '<i style="color: green" id="check" class="fas fa-check-circle"></i>';
        header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
        unset($project);


    }catch (Exception $e){
        $msg = 'an Error Occurred! try again'; //. $e->getMessage();
        $icon = '<i style="color: red" id="check" class="fas fa-exclamation-circle"></i>';
        header("location: ".$_SERVER['HTTP_REFERER']."?id=$msg&icon=$icon");
        $db->rollBack();
    }
}
?>
<div class="add" id="add2" >
    <div class="clse">
        &times;
    </div>
    <form id="pform" method="post" action="./reuseables/addproject.php" >
        <div>
            <input type="text" class="form-control input" id="pName" placeholder="project name" name="projectName" required>
        </div>

        <div id="teaMem">
            <span>Team:</span>
            <select class="form-control input" id="team" name="team" >
                <option value="<?php if(isset($_SESSION['teamId'])){echo $_SESSION['teamId'];}?>" selected><?php if(isset($_SESSION['teamId'])){echo 'MyTeam'. $_SESSION['id'];}?></option>
                <?php
                if(isset($_SESSION['id'])) {
                    try {
                        require_once './php/config.php';
                        $query = <<<SQL
                                    SELECT team_name, team_id FROM team
                                    WHERE user_id = :userId AND isDefault = 0
                                SQL;
                        $stmt = $db->prepare($query);
                        $stmt->bindValue("userId", $_SESSION['id']);
                        $stmt->execute();
                        $results = $stmt->fetchAll();
                        foreach ($results as $id) {
                        echo '<option value="' . $id['team_id'] . '">'.$id['team_name'].'</option>';
                        }
                } catch (Exception $e) {
                    echo 'Error ' . $e->getMessage();
                    }
                }
            ?>
            </select>
            <br>
            <span>Assigned to:</span>
            <select class="form-control input" id="pAssignee" name="pAssignee[]" required>
                <?php
                if (isset($_SESSION['email'])){
                    echo '<option value="' . $_SESSION['id'] .'"selected>' . $_SESSION['email'] . '</option>';
                }
                ?>
            </select>
        </div>

        <div>
            <textarea class="form-control input" id="pDescription" placeholder="description" name="pDescription" maxlength="100" rows="4" cols="50"></textarea>
        </div>
        <div>
            <input class="btn submit" type="submit" id="psubmit" value="Create">
        </div>
    </form>
</div>
