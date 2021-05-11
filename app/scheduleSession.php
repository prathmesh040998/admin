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
$uuid = uniqid();
?>
<html lang="en">

<head>
  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <!-- <script src="multiselect/jquery.multiselect.js"></script>
    <link rel="stylesheet" href="multiselect/jquery.multiselect.css">    -->
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

    <div class="container">
        <div class="row">

            <div class="col-sm-6">

                <form id="frmSession">

                    <p class="text-center font-weight-bold"> Create Sessions </p>

                    <div class="form-group">
                        <label class="col-sm-2 col-form-label">Courses</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="course_id" id="courses" required>
                                <option value="">Select Course</option>
                                <option value="1">Beginner</option>
                                <option value="2">Intermediate</option>
                                <option value="3">Advance</option>

                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 col-form-label">Lessons</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="lesson_id" id="lessons" required>
                                <option value="">First select Course</option>
                            </select>
                        </div>
                    </div>
                   

                    <div class="form-group ">
                        <label class="col-sm-2 col-form-label">Teachers</label>
                        <div class="col-sm-10">
                            <?php
                            echo '<select class="form-control"  name=teacher_id id=teacher required>';
                            foreach ($teachers as $teacher) {
                                echo "<option value=$teacher->user_id > " . ucfirst($teacher->first_name) . " "  . ucfirst($teacher->last_name)
                                    . " ( "  . ucfirst($teacher->category) . ") </option>";
                            }
                            echo '</select>';
                            ?></div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-sm-2 col-form-label">Students</label>
                        <div class="col-sm-10">
                         
                            <select class="form-control" name="student_id[]" id=student required multiple>;
                            <?php
                            foreach ($students as $student) {
                                echo "<option value=$student->user_id >" . ucfirst($student->first_name) . " "  . ucfirst($student->last_name) . " ( "  . ucfirst($student->category) . ") </option>";
                            }
                            echo '</select>';
                     

                            ?>
                            </select>
                             <!-- <input type="textbox" id="t1"> -->
                            </div>
                           
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 col-form-label">Time</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" id="meeting-time" name="sessionTime" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-form-label">Room Identifier</label>
                        <div class="col-sm-10">

                            <input type="text" id="meeting-time" class="form-control" name="room_id" value="<?= $uuid ?>" required>
                        </div>
                    </div>

                    <div class="col-auto my-1">
                            <input type="hidden" name="action" value="saveSession">
                        <input value="submit" name="submit" id="btnSaveSession" type="submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script> -->
    <!-- <link href='chosen/chosen.min.css' rel='stylesheet' type='text/css'> -->
<script src='chosen.jquery.js' type='text/javascript'></script>
   <link rel="stylesheet" href="chosen.min.css">

    <script type="text/javascript">
            var sel;
          
        $("#courses").change(function() {
            let courseId = $(this).val();
            $.post("a.php", {
                    course_id: courseId,
                    action: "getLessonByCourseId"
                })
                .done(function(data) {
                    $("#lessons").html(data);
                });
        });


    
            
                //         $("#student").change(function(){

                // //var str = ($("#student").val() || []).join(', '); 

                //                 var str=new Array();

                //                 $("#student option:selected").each(function() {

                //             str.push($(this).val());

                // });

            // $.post("a.php", data)
            //     .done(function(resultData) {
            //         alert("" + resultData);
            //         $("#response").html(resultData);
            //         window.location.reload();
            //     });
           
            // $("#student").change(function(){ // change function of listbox
            // var sel=new Array();
            // //   sel = $("#student").val(); // reading listbox selection
            // // console.log(sel);
            // $("#t1").attr('value',sel); // keeping in a text box
            //  sel.push($(this).val());
            //  $("#t1").attr('value',sel);
            // sel=sel.toString();
            // console.log(sel);
            // }); 
            $("#student").chosen();

        $("#frmSession").submit(function(e) {
             e.preventDefault();
            
            // tu call hold var 
            let data  = $('#frmSession').serialize();
            // let data = $('#frmSession').serializeArray().reduce(function(obj, item) {
            //     obj[item.name] = item.value;
               
            //     return obj;
            // }, {});
           
             data.action = "saveSession";
           
            // course_id:$('course_id').val();
            //     lesson_id:$('lesson_id').val();
            //     teacher_id:$('teacher_id').val();
            //     student_id:$('student_id').val();
            //     sessionTime:$('sessionTime').val();

            $.ajax("a.php",{
                data: data,
                action: "saveSession",
                method: "POST",
            })      

                .done(function(resultData) {
                    alert("" + resultData);
                    $("#response").html(resultData);
                    window.location.reload();
                });
        });
           

    </script>
</body>

</html>