<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kazini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="./node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="./css/homeMain.css">
    <link rel="stylesheet" type="text/css" href="./css/blocker.css">
</head>
<body>
<?php

if (session_status() == 1) {
    session_start();
}
require_once './reuseables/navbar.php';
require_once './reuseables/addtask.php';
require_once './reuseables/addproject.php';
require_once './reuseables/addteam.php';

include 'php/blocker-form.php';

?>

<div id="main">

        <?php if(isset($messages['error_messages'])): ?>
            <?php if(!empty($messages['error_messages'])): ?>
                <div class="alert alert-danger" role="alert">
                <?php foreach ($messages['error_messages'] as $error): ?>

                    <?php echo $error; ?>

                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <?php if(isset($messages['success_messages'])): ?>
        <?php if(!empty($messages['success_messages'])): ?>
            <div class="alert alert-success" role="alert">
                <?php foreach ($messages['success_messages'] as $message): ?>

                    <?php echo $message; ?>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-4">
            <p style="color: darkred">Add new blocked url</p><hr>
            <form method="post" >
                <div class="form-group blocker_group">
                    <input type="text"  class="form-control" id="site_name" value="<?php echo isset($site_name)?$site_name:"" ?>" aria-describedby="Site Name" placeholder="Enter site name"  name="site_name" required>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="url" placeholder="Enter url to block" name="url"  value="<?php echo isset($url)?$url:"" ?>" required>
                </div>
                <div class="form-check">

                </div>
                <button type="submit" class="btn btn-primary form-control" style="background: #8b0000">Submit</button>
            </form>
        </div>
        <div class="col-md-8">
            <p style="color: darkred">Blocked urls</p><hr>
            <table class="table table-hover table-sm">
                <thead class="thead">
                <tr>
                    <th scope="col" class="vCheck"></th>
                    <th scope="col" class="vdet">Site</th>
                    <th scope="col" class="vpom">URL</th>
                    <th scope="col" class="vdue">Create Date</th>
                    <th scope="col" class="vdue">Update</th>
                    <th scope="col" class="vdue">Delete</th>
                </tr>
                </thead>

                <tbody>
                <?php if(!empty($blocked_urls)): ?>
                <?php foreach ($blocked_urls as $url): ?>
                <tr>
                    <td>
                        <input type="checkbox" value="<?php echo $url['id']; ?>">


                    </td>
                    <td>
                        <?php echo $url['site_name']; ?>

                    </td>
                    <td>
                        <?php echo $url['url']; ?>

                    </td>
                    <td>
                        <?php echo $url['create_time']; ?>

                    </td>
                    <td>
                        <button class="btn btn-primary">Update</button>
                    </td>

                    <td>
                        <button class="btn btn-danger">Delete</button>
                    </td>

                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="4"> No records found!</td></tr>
                <?php endif;?>
                </tbody>

            </table>
        </div>

    </div>

</div>


</body>
<script src="./node_modules/jquery/dist/jquery.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./javascript/homePage.js"></script>
</html>