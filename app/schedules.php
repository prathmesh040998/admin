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
    if ($role == "operations") {
        header("Location: main.php");
    }
} else {
    header("Location: index.php");
}
$users = getStudents();
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>All Sessions</title>
</head>
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
<div class="container mt-2 text-center">
    <div class="form-group">
        <label><b>Select Date</b></label>
        <input type="date" id="date" class="form-control">
        <button class="btn btn-success m-2" onclick="get_session()"> Get Session by Date</button>
        <b>OR</b>
        <button class="btn btn-success m-2" onclick="get_all_session()"> Get All Upcomming Sessions</button>
    </div>

    <div id="schedule_data">
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="editSchedule">

        </div>
    </div>
</div>

<body>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function get_session() {
            let date = $("#date").val()
            // console.log(date)
            if (date != "") {
                console.log(date)
                $.post("db.php", {
                        get_session: "get_session",
                        date: date
                    })
                    .done(function(data) {

                        $("#schedule_data").empty();
                        $("#schedule_data").append(data);
                        // console.log(data);
                    });
            }
        }

        function search() {
            sortby = $("input[type='radio'][name='sortby']:checked").val();
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchName");
            filter = input.value.toUpperCase();
            table = document.getElementById("allSchedules");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[sortby];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }


        function deleteSchedule(id, comp) {
            var r = confirm("You Really want to delete schedule");
            if (r == true) {
                $.post("a.php", {
                        schedule_id: id,
                        action: "deleteSchedule"
                    })
                    .done(function(data) {
                        alert(data);
                        if (comp == "byDate") {
                            get_session();
                        } else {
                            get_all_session();
                        }
                    });
            }
        }

        function updateScheduleTimeForm() {
            let schedule_id = $("#schedule_id").val();
            let schedule_time = $("#schedule_time").val();
            // console.log(schedule_time)
            // console.log(schedule_id)
            let comp = $("#reload_comp").val();
            console.log(comp)
            if (schedule_time != "") {
                $.post("a.php", {
                        schedule_id: schedule_id,
                        schedule_time: schedule_time,
                        action: "updateScheduleTimeForm"
                    })
                    .done(function(data) {
                        alert(data);
                        if (comp == "byDate") {
                            get_session();
                        } else {
                            get_all_session();
                        }
                    });
            } else {
                alert("Enter New Time")
            }
        }


        function updateScheduleLessonForm() {
            let schedule_id = $("#schedule_id").val();
            let lesson_id = $("#lessons").val();
            // console.log(lesson_id)
            // console.log(schedule_id)
            let comp = $("#reload_comp").val();
            console.log(comp)
            if (lesson_id != "") {
                $.post("a.php", {
                        schedule_id: schedule_id,
                        lesson_id: lesson_id,
                        action: "updateScheduleLessonForm"
                    })
                    .done(function(data) {
                        alert(data);
                        if (comp == "byDate") {
                            get_session();
                        } else {
                            get_all_session();
                        }
                    });
            } else {
                alert("Select New Lesson")
            }
        }

        function editSchedule(id, reload_comp) {
            var data = `
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                     <input type="hidden" id="reload_comp" value="${reload_comp}" />
                    <input type="hidden" name="schedule_id" id="schedule_id" value="${id}" />
                        <div class="col">
                            <label class="col-sm-2 col-form-label">Courses</label>
                            <select class="form-control" name="course_id" id="courses" onchange="updateLesson()" required>
                            <option value="">Select Course</option>
                            <option value="1">Beginner</option>
                            <option value="2">Intermediate</option>
                            <option value="3">Advance</option>
                        </select>
                        </div>
                        <div class="col">
                            <label class="col-sm-2 col-form-label">Lessons</label>
                             <select class="form-control" name="lesson_id" id="lessons" required>
                                <option value="">First select Course</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        <label class="col-sm-2 col-form-label">	&nbsp;</label>
                        <br>
                        <button onclick="updateScheduleLessonForm()" class="btn btn-sm btn-success">Update lesson</button>
                        </div>
                    </div>
                    <div class="form-row">
                    <input type="hidden" name="schedule_id" value="${id}" />
                        <div class="col">
                            <label class="col-sm-2 col-form-label">Time</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" class="form-control" id="schedule_time" name="sessionTime" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                        <label class="col-sm-2 col-form-label">	&nbsp;</label>
                        <br>
                        <button onclick="updateScheduleTimeForm()" class="btn btn-sm btn-success">Update time</button>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $("#editSchedule").empty();
            $("#editSchedule").append(data);
        }


        function updateLesson() {
            let courseId = $("#courses").val();
            console.log(courseId);

            $.post("a.php", {
                    course_id: courseId,
                    action: "getLessonByCourseId"
                })
                .done(function(data) {
                    $("#lessons").html(data);

                });
        }

        // $("#courses").change(function() {
        //     console.log("call")
        //     let courseId = $(this).val();
        //     $.post("a.php", {
        //             course_id: courseId,
        //             action: "getLessonByCourseId"
        //         })
        //         .done(function(data) {
        //             $("#lessons").html(data);
        //         });
        // });

        function get_all_session() {
            $.post("db.php", {
                    get_all_session: "get_all_session",
                })
                .done(function(data) {
                    $("#schedule_data").empty();
                    $("#schedule_data").append(data);
                    // console.log(data);
                });
        }
    </script>
</body>

</html>