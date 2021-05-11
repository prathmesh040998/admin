<?php
session_start();
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
include_once("db.php");
$username = "";
$loggedIn = false;
$role = "";
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
    $username = $_SESSION["username"];
    $role = $_SESSION["role"];
    $loggedIn = $_SESSION["loggedIn"];
    // echo $role;
} else {
    header("Location: index.php");
}
?>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">

    <title>Code Gurukul</title>
    <style>
        .navbar {
            font-size: 12px;
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .navbar-brand {
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .table td,
        .table th {

            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        .input-group-text {
            font-size: 12px;
        }

        .col {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-0">
        <a class="navbar-brand p-0" href="main.php">
            <img src="./logo.svg" width="120" height="90" alt="" loading="lazy">
        </a>
        <!-- <a class="navbar-brand" href="#">Code Gurukul</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>
            <span class="nav-item float-right">
                <?php
                if ($loggedIn) {
                    echo '       
          <a class="nav-link" href="logout.php" tabindex="-1" >Logout</a>
       ';
                }
                ?>
            </span>
        </div>
    </nav>

    <div class="container">
        <div class="row">

            <div class="col-sm-6">
                <br />
                <br />
                <br />
                <br />
                <div class="form-group">
                    <p>1. <a href="add_user.php">Add New User</a></p>
                </div>
                <div>
                    <p>2. <a href="edit_user.php">Edit User</a></p>
                </div>
                <div>
                    <p>3. <a href="scheduleSession.php">Schedule Class</a></p>
                </div>
                <div>
                    <p>4. <a href="schedules.php">View, Edit And Delete Schedule Class</a></p>
                </div>
                <?php
                if ($role != 'operations') {
                ?>
                    <div>
                        <p>5. <a href="mapping.php">Student Teacher Mapping</a></p>
                    </div>
                    <div>

                        <p>6. <a href="createLessons.php">Manage Courses-Lessons-links</a></p>
                    </div>
                <?php
                }
                ?>
            </div>



        </div>
    </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript">
        $("#generatePassword").click(function() {
            //            
            var password = $("#txtPassword").val();

            $.post("a.php", {
                    'p': password,
                    'action': 'generatePassword'
                })
                .done(function(resultData) {
                    $("#password").html(resultData);
                });
        });
    </script>
</body>

</html>