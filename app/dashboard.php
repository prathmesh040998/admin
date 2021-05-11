<?php
session_start();
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
include_once("db.php");
$username = "";
$loggedIn = false;
$role = "";
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true){
  $username = $_SESSION["username"];
  $role = $_SESSION["role"];
  $loggedIn = $_SESSION["loggedIn"];

  
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
            .navbar{
                font-size: 12px;
                padding-top:  0px;
                padding-bottom:  0px;
            }
            .navbar-brand {
                padding-top:  0px;
                padding-bottom:  0px; 
            }
            .table td, .table th {

                font-size: 12px;
            }
            .btn {
                font-size: 12px;
            }
            .input-group-text{
                font-size: 12px;
            }
            .col{
                margin-left: 20px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">
                <img src="./logo.svg" width="100" height="70" alt="" loading="lazy">
            </a>
            <!-- <a class="navbar-brand" href="#">Code Gurukul</a> -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
               


                <span class="nav-item float-right">
                    <?php
                    if ($loggedIn) {
                        echo '       
          <a class="nav-link" href="logout.php" tabindex="-1" >Logout</a>
       ';
                    }
                    ?>
                </span>
                <!-- <form class="form-inline my-2 my-lg-0">
                  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Book Free Trial / Sign Up</button>
                </form> -->
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
                        <label class="col-form-label">1. Generate Password:</label>
                        <div class="col-sm-10">

                            <input type="text" id="txtPassword"
                                   name="txtPassword" 
                                   />
                            <button id="generatePassword" type="button" class="btn btn-primary">Generate Password</button>
                            <div id="password"></div>
                        </div>
                    </div>
                    <div>
                        
                        <p>2. <a href="db1.php">Database Link</a></p>
                    </div>
                    <div>
                     
                        <p>3. <a href="createSession.php">Create Session Link</a></p>
                    </div>
                    <div>
                        
                        <p>4. <a href="schedule.php">View all Sessions</a></p>
                    </div>
                    <div>
                        
                        <p>5. <a href="register.php">Register Teacher</a></p>
                    </div>
                    <div>
                        
                        <p>6. <a href="loginas.php">Login As</a></p>
                    </div>
                </div>



            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript">

        $("#generatePassword").click(function () {
//            
            var password = $("#txtPassword").val();

            $.post("a.php", {'p': password, 'action': 'generatePassword'})
                    .done(function (resultData) {
                        $("#password").html(resultData);
                    });
        });

    </script>
</body>
</html>