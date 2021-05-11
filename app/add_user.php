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
} else {
    header("Location: index.php");
}

$courses = getCourses();
$teachers = getTeachers();
$students = getStudents();
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
                <a class="nav-link" href="main.php" tabindex="-1">My Dashboard</a>
            </span>


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


    <div class="container mt-5 mb-5">
        <h5 class="text-center"><b>Add New User</b></h5>
        <form method="post" id="userData">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required />
                <div id="usernameError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" required />
                <div id="emailError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <?php
                    if ($role != "operations") {
                    ?>
                        <option value="teacher">Teacher</option>
                    <?php
                    }
                    ?>
                    <option value="student">Student</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">First Select role</option>
                </select>
            </div>

            <div class="mb-3">
                <button class="form-control">submit</button>
            </div>
        </form>
    </div>
    <!-- script -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#userData").submit(function(event) {
                event.preventDefault();
                if (checkuser() && checkemail()) {
                    let data = $("#userData").serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});
                    data.action = "saveUser";
                    $.post("a.php", data)
                        .done(function(resultData) {
                            alert("" + resultData);
                            location.reload();
                        });
                } else {
                    alert("enter valid username and email")
                }
            });

            $("#role").change(function() {
                if ($("#role").val() == "student") {
                    $('#category')
                        .empty()
                        .append('<option value="">Select Category</option>\n' +
                            '<option value="beginner">Beginner</option>\n' +
                            '<option value="intermediate">Intermediate</option>\n' +
                            '<option value="advanced">Advanced</option>');
                } else if ($("#role").val() == "teacher") {
                    $('#category')
                        .empty()
                        .append('<option value="">Select Category</option>\n' +
                            '<option value="beginner">Beginner</option>\n' +
                            '<option value="intermediate">Intermediate</option>\n' +
                            '<option value="advanced">Advanced</option>\n' +
                            '<option value="beginner_intermediate">Beginner+Intermediate</option>\n' +
                            '<option value="beginner_advanced">Beginner+Advanced</option>\n' +
                            '<option value="intermediate_advanced">Intermediate+Advanced</option>\n' +
                            '<option value="beginner_intermediate_advanced">Beginner+Intermediate+Advanced</option>');
                } else {
                    $('#category')
                        .empty()
                        .append('<option value="">First Select role</option>');
                }
            })

            $("#username").change(function() {
                checkuser();
            });

            $("#email").change(function() {
                checkemail();
            });
        });


        function checkuser() {
            username = $("#username").val();
            console.log(username);
            // console.log("The text has been changed." + username.length);
            if (username.length >= 4) {
                var user = new FormData();
                user.append("username", username);
                user.append("action", "checkUsername");
                var status;
                $.ajax({
                    async: false,
                    url: "a.php",
                    type: "POST",
                    dataType: "script",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: user,
                    success: function(data) {
                        if (data == 1) {
                            $("#usernameError").html(
                                "<span class='text-success'><small>✶ Username Available</small></span>"
                            );
                            status = true;
                        } else {
                            $("#usernameError").html(
                                "<span class='text-danger'><small>✶ all ready taken</small></span>"
                            );
                            // console.log("all ready taken");
                            status = false;
                        }
                    },
                });
                return status;
            } else {
                $("#usernameError").html(
                    "<span class='text-danger'><small>✶ Username must 4 character</small></span>"
                );
                return false;
            }
        }



        function checkemail() {
            var email = $("#email").val();
            console.log(email);
            // console.log("The text has been changed." + username.length);

            var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if (email == "" || !email.match(pattern)) {
                $("#emailError").html(
                    "<span class='text-warning'><small>✶ Enter Valid Email</small></span>"
                );
                return false;
            } else {
                var user = new FormData();
                user.append("email", email);
                user.append("action", "checkEmail");
                var status;
                $.ajax({
                    async: false,
                    url: "a.php",
                    type: "POST",
                    dataType: "script",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: user,
                    success: function(data) {
                        console.log(data)
                        if (data == 1) {
                            $("#emailError").html(
                                "<span class='text-success'><small>✶ Email Available</small></span>"
                            );
                            status = true;
                        } else {
                            $("#emailError").html(
                                "<span class='text-danger'><small>✶ all ready taken</small></span>"
                            );
                            status = false;
                        }
                    },
                });
            }
            return status;
        }
    </script>
</body>

</html>