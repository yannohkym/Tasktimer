<?php
if(session_status() == 1){
    session_start();
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
    <link rel="stylesheet" type="text/css" href="./css/task.css">
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
    <div>
        <select id="tCategory">
            <option value="0" selected>Incomplete task</option>
            <option value="1" >completed tasks</option>
            <option value="2" >All tasks</option>
            <option value="3" >Assigned to others</option>
        </select>
    </div>
    <div>
        <table class="table table-hover table-sm">
            <thead class="thead">
            <tr>
                <th scope="col" class="vCheck"></th>
                <th scope="col" class="vdet">Task Details</th>
                <th scope="col" class="vpom">Pomodoro</th>
                <th scope="col" class="vdue">Due</th>
                <th scope="col" class="vpro">Project</th>
                <th scope="col" class="vstat"></th>
            </tr>
            </thead></table>
    </div>
<div id="section">
    <div class="vsection">
        <p style="color: darkred">Recently added</p><hr>
        <div id="rAdded">
            <table class="table table-hover table-sm">
                <tbody id="recent">

                </tbody>
            </table>
            <p id="recent1" style="color: gray; text-align: center">oops! no task added recently</p>
        </div>
    </div>

    <div class="vsection">
        <p style="color: darkred">Due Today</p><hr>
        <div id="dToday">
            <table class="table table-hover table-sm">
                <tbody id="today">

                </tbody>
            </table>
            <p id="today1" style="color: gray; text-align: center">Hooray! no task due today</p>
        </div>
    </div>

    <div class="vsection">
        <p style="color: darkred">Upcoming</p><hr>
        <div id="soon">
            <table class="table table-hover table-sm">
                <tbody id="upcoming">

                </tbody>
            </table>
            <p id="upcoming1" style="color: gray; text-align: center">Oops! no upcoming tasks for you</p>
        </div>
    </div>

    <div class="vsection">
        <p style="color: darkred">Later</p><hr>
        <div id="lat">
            <table class="table table-hover table-sm">
                <tbody id="later">

                </tbody>
            </table>
            <p id="later1" style="color: gray; text-align: center">Oops! no tasks here</p>
        </div>
    </div>

    <div class="vsection">
        <p style="color: darkred">Overdue</p><hr>
        <div id="oDue">
            <table class="table table-hover table-sm">
                <tbody id="overdue">

                </tbody>
            </table>
            <p id="overdue1" style="color: gray; text-align: center">Hooray! no overdue tasks</p>
        </div>
    </div>
</div>

</div>
<div id="vtoast"></div>

<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
<script src="./node_modules/moment/min/moment.min.js"></script>
<script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="./javascript/homeMain.js"></script>
<script src="./javascript/task.js"></script>
</body>

</html>