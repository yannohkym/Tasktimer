<div id="head">
    <h1>
        <span id="title">
          T<i style="color: darkred" class="fas fa-apple-alt"></i>mer
        </span>

        <div id="id_icon">
            <span class="icon dropdown">
                <i data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus-circle dropdown-toggle"></i>
                </i>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                        <li><a id="addT" class="dropdown-item" href="#">
                                <i class="fas fa-plus-circle"></i> Add task</a>
                        </li>
                        <li><a id="addP" class="dropdown-item" href="#">
                                <i class="fas fa-plus-circle"></i> Add project</a>
                        </li>
                        <li><a id="addTm" class="dropdown-item" href="#">
                                <i class="fas fa-plus-circle"></i> Add Team</a>
                        </li>
                    </ul>
            </span>
            <span class="icon dropdown">
                    <i data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i><span id="bell"></span>
                </i>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                        <li><a class="dropdown-item mytask" href="./Inbox.php">notifications<span></span></a></li>
                    </ul>
            </span>
            <span class="icon dropdown">
                <i data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </i>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
<!--                        <li><a class="dropdown-item" href="">User profile</a></li>-->
                        <li><a class="dropdown-item" href="pomodoro.php">pomodoro</a></li>
 HEAD
<!--                        <li><a class="dropdown-item" href="blocker.php">blocker</a></li>-->
=======
                        <li><a class="dropdown-item" href="">blocker</a></li>
 aad93123e83860c0e59ece7950628a486d499f60
                         <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?logout=true" id="logOut">logOut</a> </li>
                    </ul>
            </span>
        </div>
        <div id="dropdown">
            <span class="span"></span>
            <span class="span"></span>
            <span class="span"></span>
        </div>
    </h1>
</div>
<div id="nav">
    <nav id="navbar">
        <div>
            <div class="closebtn">
                &times;
            </div>
                <div id="profile">
                        <?php
                        if($_SESSION['picture']){
                            echo '<img src="' . $_SESSION["picture"] . '"> <p>' . $_SESSION['name'] . '</p>';
                        }else{
                            echo '<img src="Images/profilePic.jpg"> <p>' . $_SESSION['name'] . '</p>';
                        }
                        ?>
                </div>
            <ul>
                <li>
                    <a id="navhome" class="nav-link" href="./homePage.php">
                        Home
                    </a>
                </li>
                <li>
                    <a id="navtask" class="nav-link"  href="./myTasks.php">
                        MyTasks
                    </a>
                </li>
                <li>
                    <a id="navbox" class="nav-link mytask" href="./Inbox.php">
                        Inbox<span></span>
                    </a>
                </li>
                <li>
                    <span id="navteam" class="nav-link" style="text-align: center">
                        <i class="fas fa-caret-down"></i>Teams
                    </span>
                    <div id="teamlist">
                        <?php
                        if(isset($_SESSION['id'])){
                            try{
                                require_once './php/config.php';
                                $query1 = <<<SQL
                                SELECT team_name, team_id
                                FROM team
                                WHERE user_id = :id
                            SQL;
                                $stmt1 = $db->prepare($query1);
                                $stmt1->bindValue('id', $_SESSION['id']);
                                $stmt1->execute();
                                $results1 = $stmt1->fetchAll();
                                foreach ($results1 as $result){
                                    echo '<p class="teamG" id="'.$result['team_id'].'">'.$result['team_name'].'</p>';
                                }

                                $qu = <<<SQL
                                SELECT t.team_name, t.team_id
                                FROM team_Membership tM
                                INNER JOIN team t on tM.team_id = t.team_id
                                WHERE tM.user_id = :id;
                            SQL;
                                $st = $db->prepare($qu);
                                $st->bindValue('id', $_SESSION['id']);
                                $st->execute();
                                $re = $st->fetchAll();
                                foreach ($re as $result){
                                    echo '<p class="teamG" id="'.$result['team_id'].'">'.$result['team_name'].'</p>';
                                }

                            }catch (Exception $e){
                                echo $e->getMessage();
                            }

                        }

                        ?>
                    </div>
                </li>
                <li>
                    <a id="navpom" class="nav-link" href="./pomodoro.php">
                        Pomodoro<i style="color: darkred" class="fas fa-apple-alt"></i>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a id="navblo" class="nav-link" href="./blocker.php">-->
<!--                        Blocker-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
    </nav>
</div>