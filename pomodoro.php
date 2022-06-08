<?php
if(session_status() == 1){
    session_start();
}
if(isset($_GET['pomid'])){
    $_SESSION['pomId'] = $_GET['pomid'];
}

?>
<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Kazini</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
            <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
            <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
            <link rel="stylesheet" href="./node_modules/multiple-select/dist/multiple-select.min.css">
            <link rel="stylesheet" href="./dateTime/build/css/bootstrap-datetimepicker.min.css">
            <link rel="stylesheet" type="text/css" href="./css/homeMain.css">
            <link rel="stylesheet" type="text/css" href="./css/pomodoro.css">
        </head>
        <script>
            if(typeof window.history.pushState == 'function') {
                window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
            }
        </script>
<body>
<?php
require_once './reuseables/navbar.php';
require_once './reuseables/addtask.php';
require_once './reuseables/addproject.php';
require_once './reuseables/addteam.php';
require_once './reuseables/toast.php';
require_once './reuseables/taskView.php';
?>

<div id="main">
    <div id="pomodoroN">
        <?php
        if(isset($_SESSION['pomId'])){
            try {
                require_once './php/config.php';

                $q = <<<SQL
                SELECT task_name
                FROM task
                WHERE id = :id;
            SQL;
                $s = $db->prepare($q);
                $s->bindValue("id", $_SESSION['pomId']);
                $s->execute();
                $results = $s->fetchAll();

                echo ' <p class="pomtaskN" id="'.$_SESSION['pomId'].'">'.$results[0]['task_name'].'</p>
           <div>
                    <span id="config"><i class="fas fa-cog"></i></span>
                </div>
        
            </div>
            <hr>  
              <div id="pomform">
        <label>Work Duration:
            <input type="number" class="form-control pominput" id="work" placeholder="minutes" name="duration">
        </label>
        <label>Short Break Duration:
            <input type="number" class="form-control pominput" id="short" placeholder="minutes" name="short_break">
        </label>
        <label>Long Break Duration:
            <input type="number" class="form-control pominput" id="long" placeholder="minutes" name="long_break">
        </label>
        <label>Long Break Interval:
            <input type="number" class="form-control pominput" id="interval" placeholder="minutes" name="intervals">
        </label>
        <div>
            <button class="btn submit" id="pomsub">Update</button>
        </div>
    </div> 
           ';
            }catch (Exception $e){
                echo $e->getMessage();
            }
        }else{
            echo '<p class="pomtaskN"  id="default">Pomodoro<i style="color: darkred" class="fas fa-apple-alt"></i></p>
          <div>
                    <span id="config"><i class="fas fa-cog"></i></span>
                </div>
        
            </div>
              <div id="pomform">
        <label>Work Duration:
            <input type="number" class="form-control pominput" id="work" placeholder="minutes" name="work">
        </label>
        <label>Short Break Duration:
            <input type="number" class="form-control pominput" id="short" placeholder="minutes" name="short">
        </label>
        <label>Long Break Duration:
            <input type="number" class="form-control pominput" id="long" placeholder="minutes" name="long">
        </label>
        <label>Long Break Interval:
            <input type="number" class="form-control pominput" id="interval" placeholder="minutes" name="interval">
        </label>
        <div>
            <button class="btn submit" id="pomsub">Update</button>
        </div>
    </div>
            <hr>';
        }
        ?>
        <p id="pomrem">Pomodoro remaining<br>
            <span id="subtract"> - </span>
            <span id="pomNo"></span>
            <span id="add"> + </span>
        </p>

        <div id="message">
            Work time! Stay focus
        </div>

    <div id="pomodoro">
        <div id="Thepomodoro">
            <i class="fas fa-apple-alt"></i>
            <div id="app"></div>
            <span id="span" style="display: flex">
                <button class="button" id="buttonstart"><i class="far fa-play-circle"></i>start</button>
                <button class="button" id="buttonresume" style="background-color: yellowgreen; display: none"><i class="far fa-play-circle"></i>Resume</button>
                <button class="button" id="buttonpause" style="background-color: darkred; display: none"><i class="far fa-pause-circle"></i>pause</button>
                <button class="button"  id="buttonstop" style="background-color: red"><i class="fas fa-stop-circle"></i>Stop</button>
            </span>
        </div>

    </div>


        <div id="toast"></div>

</div>


    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
    <script src="./node_modules/moment/min/moment.min.js"></script>
    <script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="./node_modules/easytimer/dist/easytimer.min.js"></script>
    <script src="./javascript/homeMain.js"></script>
    <script src="./javascript/pomodoro.js"></script>
    <script src="./javascript/timer.js"></script>

</body>
</html>