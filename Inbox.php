<?php
if(session_status() == 1){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task timer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" href="./node_modules/multiple-select/dist/multiple-select.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap4-toggle/css/bootstrap4-toggle.min.css">
    <link rel="stylesheet" href="./dateTime/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="./css/inbox.css">
    <link rel="stylesheet" type="text/css" href="./css/homeMain.css">

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
        <button id="icheck" class="icomplete"><i style="color: gray" class="far fa-check-circle id" id="0"></i><span> Unread only</span></button>
    </div>
    <div id="inbox">
        <div id="preview">

        </div>
        <div id="view">
            <div class='iadd' id="iview1" >
                <div>
                    <button id="icomplete" class="icomplete"><i style="color: gray" class="far fa-check-circle"></i><span> Incomplete</span></button>
                </div>
                <div>
                    <p style="color: slateblue; font-size: 20px" id="imess">

                    </p>
                </div>
                <div>
                    <p id="iName"></p>
                </div>
                <div id="iAssign">
                    <p class="idiv" id="divAss">
                        <span class="label" >Assigned to:</span><br>
                        <span id="iAssignee"></span>
                    </p>
                    <p class="idiv" id="divBy">
                        <span class="label">By:</span><br>
                        <span id="iAssigner"></span>
                    </p>
                   <p class="idiv" id="ilabel">
                       <span class="label" >In:</span><br>
                       <span id="iProject" ></span>
                   </p>
                </div>
                <p class="idiv">
                    <span class="label">Description:</span><br>
                    <span id="iDescription"></span>
                </p>
                <p class="idiv" id="divPom">
                    <span class="label">pomodoro:<br></span>
                    <span id="ipomodoro"></span>
                </p>
                <p class="idiv" id="divdat">
                    <span class="label">Due:</span><br>
                    <span id="iDate">today</span>
                </p>
            </div>

            <p class="nothing">select a notification to view</p>
        </div>
    </div>
</div>
</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./node_modules/multiple-select/dist/multiple-select.min.js"></script>
<script src="./node_modules/moment/min/moment.min.js"></script>
<script src ="./dateTime/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="./node_modules/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
<script src="./javascript/homeMain.js"></script>
<script src="./javascript/inbox.js"></script>
</html>