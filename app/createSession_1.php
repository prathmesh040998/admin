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

$courses = getCourses();
$teachers = getTeachers();
$students = getStudents();
$uuid = uniqid();
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
                    <a class="nav-link" target="_blank" href="dashboard.php" tabindex="-1" >My Dashboard</a>
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
                 <!-- <form class="form-inline my-2 my-lg-0">
                  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Book Free Trial / Sign Up</button>
                </form> -->
            </div>
        </nav>

        <div class="container">
            <div class="row">

                <div class="col-sm-6">
                    
                    <form id="frmSession">

                        <p class="text-center font-weight-bold"> Create  </p>

                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Courses</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="course_id" id="courses">
                                    <option value="">Select Course</option>
                                    <option value="1">Beginner</option>
                                    <option value="2">Intermediate</option>
                                    <option value="3">Advance</option>

                                </select>
                            </div
                        </div>
                        <div  class="form-group">    
                            <label class="col-sm-2 col-form-label">Lessons</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="lesson_id" id="lessons">
                                    <option value="">First select Course</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-2 col-form-label">Teachers</label>
                            <div class="col-sm-10">
                                <?php
                                echo '<select class="form-control"  name=teacher_id id=teacher>';
                                foreach ($teachers as $teacher) {
                                    echo "<option value=$teacher->user_id > " . ucfirst($teacher->first_name) ." "  .ucfirst($teacher->last_name) ." </option>";
                                }
                                echo '</select>';
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Students</label>
                            <div class="col-sm-10">
                                <?php
                                echo '<select class="form-control" name=student_id id=student>';
                                foreach ($students as $student) {
                                    echo "<option value=$student->user_id >".ucfirst($student->first_name) ." "  .ucfirst( $student->last_name) ." ( "  .ucfirst($teacher->category).") </option>";
                                }
                                echo '</select>';
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Time</label>
                            <div class="col-sm-10">

                        <input type="datetime-local" id="meeting-time"
                               name="sessionTime" 
                               >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Room Identifier</label>
                            <div class="col-sm-10">

                        <input type="text" id="meeting-time"
                               name="room_id" value="<?= $uuid ?>"
                               >
                            </div>
                        </div>
                </div>
                <div class="col-auto my-1">
                    <button id="btnSaveSession" type="button" class="btn btn-primary">Submit</button>
    </div>
                </form>
                    
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript">
        $("#courses").change(function () {
            let courseId = $(this).val();
            $.post("a.php", {course_id: courseId, action: "getLessonByCourseId"})
                    .done(function (data) {
                         $("#lessons").html(data);
                    });
        });
        $("#btnSaveSession").click(function () {
//            
            let data = $('#frmSession').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
             data.action = "saveSession";
                     var date = new Date(data.sessionTime); 
                    // var date1 = new Date(+date+date.getTimezoneOffset()*60000)
                data.sessionTime =  date.toISOString().slice(0, 19).replace('T', ' '); 
            alert (date);
            
            $.post("a.php", data)
                    .done(function (resultData) {
                        alert("" + resultData);
                        $("#response").html(resultData);
                    });
        });
        
    </script>
</body>
</html>