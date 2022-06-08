
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
        <p>Pomodoro<i style="color: darkred" class="fas fa-apple-alt"></i></p>
        <div>
            <span id="config"><i class="fas fa-cog"></i></span>
        </div>

    </div>
    <hr>
    <div id="pomform">
        <form method="post" action="" >
            <label>Work Duration:
                <input type="number" class="form-control input" id="work" placeholder="minutes" name="work" value="25">
            </label>
            <label>Short Break Duration:
                <input type="number" class="form-control input" id="short" placeholder="minutes" name="short" value="5">
            </label>
            <label>Long Break Duration:
                <input type="number" class="form-control input" id="long" placeholder="minutes" name="long" value="15">
            </label>
            <label>Long Break Interval:
                <input type="number" class="form-control input" id="interval" placeholder="minutes" name="interval" value="4">
            </label>
            <div>
                <input class="btn submit" type="submit" id="pomsub" value="Save">
            </div>
        </form>
    </div>
    <div id="pomodoro">
        <p id="pomrem">Pomodoro remaining<br>
            <span><i style="color: darkred" class="fas fa-apple-alt"></i><i style="color: darkred" class="fas fa-apple-alt"></i></span>
        </p>
        <div id="Thepomodoro">
            <i style="color: darkred" class="fas fa-apple-alt"></i>
            <span style="padding: 15px">
                <button id="button">start</button>
            </span>
        </div>


    </div>
    <div id="app"></div>

</div>

<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
<script src="./node_modules/moment/min/moment.min.js"></script>
<script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="./node_modules/easytimer.js/dist/easytimer.min.js"></script>
<script src="./javascript/homeMain.js"></script>
<script src="./javascript/pomodoro.js"></script>

</body>
</html>