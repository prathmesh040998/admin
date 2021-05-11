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

$courses = getCourses();
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

        .lesson_checkbox {
            display: inline;
        }

        .box {

            cursor: move;
        }

        #formLesson {
            position: absolute;
            top: 100px;
            right: 10px;
            background-color: #ccffff;
            z-index: 10;
        }
    </style>
    <!-- <script>
        function dragstart_handler(ev) {
            ev.dataTransfer.setData('text/plain', ev.target.id);
        }

        window.addEventListener('DOMContentLoaded', () => {
            // Get the element by id
            const element = document.getElementById('tr_11');
            // Add the ondragstart event listener
            element.addEventListener('dragstart', dragstart_handler);
        });
    </script> -->
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
                <p class="text-center font-weight-bold"> Create Lessons and Links </p>
                <div class="form-group">
                    <label class="col-sm-2 col-form-label">Courses</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="course_id" id="courses">
                            <option value="">Select Course</option>
                            <option value="1">Beginner</option>
                            <option value="2">Intermediate</option>
                            <option value="3">Advance</option>

                        </select>
                        <!-- <button class="btn  btn-info">Sort Lesson</button> -->

                    </div>
                </div>
            </div>
            <div class="col-sm-3 mt-5">
                <a href="sortingLesson.php" target="_blank">
                    <button class="btn  btn-info">Sort Lessons</button>
                </a>
                <a href="sortingLink.php" target="_blank">
                    <button class="btn  btn-info">Sort links</button>
                </a>
            </div>
        </div>
        <div class="col-sm-1">
        </div>
        <div id=formLesson class="col-sm-4">
            <input class=form_checkbox type=checkbox> Create Lessons </input>
            <form id="frmLesson" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-sm-4 col-form-label">Lesson name</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="lesson_name">
                        </input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-form-label">Project Link</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="link">

                        </input>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 col-form-label">Project name</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="project_name">

                        </input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-6 col-form-label">Project Description</label>
                    <div class="col-sm-10">
                        <!-- <textarea class="form-control" name="project description"></textarea> -->
                        <input type=file class="form-control" name="project_description">

                        </input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-form-label">Teacher Doc</label>
                    <div class="col-sm-10">

                        <input type=text class="form-control" name="teacher_doc">

                        </input>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-form-label">Student doc</label>
                        <div class="col-sm-10">
                            <input type=text class="form-control" name="student_doc">

                            </input>
                        </div>

                        <input type="hidden" name="action" value="saveLesson" />
                        <div class="col-auto my-1">
                            <button id="btnSaveLesson" type="submit" class="btn btn-primary">Submit</button>

                        </div>
                        <!--                        <div  class="form-group">    
                                                       <label class="col-sm-2 col-form-label">Lessons</label>
                                                       <div class="col-sm-10">
                                                           <select class="form-control" name="lesson_id" id="lessons">
                                                               <option value="">First select Course</option>
                                                           </select>
                                                       </div>
                                                   </div>-->

                    </div>
                    <!--                <div class="col-auto my-1">
                                            <button id="btnSaveSession" type="button" class="btn btn-primary">Submit</button>
                            </div>-->
            </form>

        </div>
        <hr>

    </div>
    </div>


    Lessons:
    <div id="lessonsGrid"> Select course to see lessons

    </div>
    </div>

    <!-- edit lesson modal -->
    <div class="modal fade" id="editLessonModal" tabindex="-1" aria-labelledby="editLessonModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLessonModal">
                        Edit Lesson details
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editLessonId" value="11" />
                    <!-- lesson name -->
                    <span>Lesson Name </span>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" class="defaultChecked" aria-label="Checkbox for following text input" value="lessonName" />
                            </div>
                        </div>

                        <input type="text" class="form-control defaultDisabled" aria-label="Text input with checkbox" id="lessonNameInput" disabled />
                        <div class="input-group-prepend p-0">
                            <div class="input-group-text p-0">
                                <button type="button" id="lessonNameButton" class="btn btn-sm btn-success defaultDisabled" disabled onclick="editLessonName()">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- lesson name end -->
                    <!-- Lesson name -->
                    <span>Project Name</span>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" class="defaultChecked" aria-label="Checkbox for following text input" value="projectName" />
                            </div>
                        </div>
                        <input type="text" class="form-control defaultDisabled" aria-label="Text input with checkbox" id="projectNameInput" disabled />
                        <div class="input-group-prepend p-0">
                            <div class="input-group-text p-0">
                                <button type="button" id="projectNameButton" class="btn btn-sm btn-success defaultDisabled" disabled onclick="editProjectName()">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- project name end -->


                    <!-- project desc name -->
                    <span>Project Desc</span>
                    <form id="edit_project_desc">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" class="defaultChecked" aria-label="Checkbox for following text input" value="projectDesc" />
                                </div>
                            </div>

                            <input type="file" name="new_project_description" class="form-control defaultDisabled" aria-label="Text input with checkbox" id="projectDescInput" disabled required />
                            <div class="input-group-prepend p-0">
                                <div class="input-group-text p-0">
                                    <button type="submit" id="projectDescButton" class="btn btn-sm btn-success defaultDisabled" disabled>
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- project desc end -->

                    <!-- project link name -->
                    <span>Project Link</span>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" class="defaultChecked" aria-label="Checkbox for following text input" value="projectLink" />
                            </div>
                        </div>

                        <input type="text" class="form-control defaultDisabled" aria-label="Text input with checkbox" id="projectLinkInput" disabled />
                        <div class="input-group-prepend p-0">
                            <div class="input-group-text p-0">
                                <button type="button" id="projectLinkButton" class="btn btn-sm btn-success defaultDisabled" disabled onclick="editProjectLink()">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- project link end -->

                    <!-- teacher doc start -->

                    <span>Teacher Doc</span>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" class="defaultChecked" aria-label="Checkbox for following text input" value="teacherDoc" />
                            </div>
                        </div>

                        <input type="text" class="form-control defaultDisabled" aria-label="Text input with checkbox" id="teacherDocInput" disabled />
                        <div class="input-group-prepend p-0">
                            <div class="input-group-text p-0">
                                <button type="button" id="teacherDocButton" class="btn btn-sm btn-success defaultDisabled" disabled onclick="editTeacherDoc()">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- teacher doc end -->
                    <!-- teacher doc start -->

                    <span>Student Doc</span>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" class="defaultChecked" aria-label="Checkbox for following text input" value="studentDoc" />
                            </div>
                        </div>

                        <input type="text" class="form-control defaultDisabled" aria-label="Text input with checkbox" id="studentDocInput" disabled />
                        <div class="input-group-prepend p-0">
                            <div class="input-group-text p-0">
                                <button type="button" id="studentDocButton" class="btn btn-sm btn-success defaultDisabled" disabled onclick="editStudentDoc()">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- teacher doc end -->
                </div>
            </div>
        </div>
    </div>



    <!-- view project Modal -->
    <div class="modal fade" id="showProjectDocModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="" id="projectDoc"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">
        $("#courses").change(function() {
            getLessonByCourseIdGrid();
        });

        function showProjectDoc(url, file_name) {
            url = "https://admin.code-gurukul.com/uploads/" + file_name;
            ext = file_name.split('.').pop();
            $("#projectDoc").empty();
            if (ext == "pdf") {
                $("#projectDoc").html(
                    `<iframe
            src="${url}#toolbar=0&navpanes=0"
            style='height: 75vh; width: 100%;'
            frameborder="0"
            >
        </iframe>`
                );
            } else {
                $("#projectDoc").html(
                    `<iframe
            style='height: 75vh; width: 100%;'
            src="https://view.officeapps.live.com/op/embed.aspx?src=${url}"
            >
        </iframe>`
                );
            }
        }



        // edit lesson
        $('input[type="checkbox"]').change(function() {
            if (this.checked) {
                var i = "#" + this.value + "Input";
                var b = "#" + this.value + "Button";
                $(i).prop("disabled", false);
                $(b).prop("disabled", false);
            } else {
                var i = "#" + this.value + "Input";
                var b = "#" + this.value + "Button";
                $(i).prop("disabled", true);
                $(b).prop("disabled", true);
            }
        });

        function editLesson(lesson_id, lesson_name, project_name, project_link, teacher_doc, student_doc) {
            $(".defaultChecked").prop("checked", false);
            $(".defaultDisabled").prop("disabled", true);
            $("#editLessonId").val(lesson_id);
            $("#lessonNameInput").val(lesson_name);
            $("#projectNameInput").val(project_name);
            $("#projectLinkInput").val(project_link);
            $("#teacherDocInput").val(teacher_doc);
            $("#studentDocInput").val(student_doc);

        }

        function editLessonName() {
            let lesson_id = $("#editLessonId").val();
            let new_lesson_name = $("#lessonNameInput").val();
            console.log(lesson_id + " " + new_lesson_name);
            if (new_lesson_name != "") {
                $.post("db.php", {
                        lesson_id: lesson_id,
                        lesson_name: new_lesson_name,
                        edit_lesson_name: "edit_lesson_name"
                    })
                    .done(function(data) {
                        alert(data);
                        getLessonByCourseIdGrid();
                        $(".defaultChecked").prop("checked", false);
                        $(".defaultDisabled").prop("disabled", true);
                    });
            }

        }

        function editProjectName() {
            let lesson_id = $("#editLessonId").val();
            let new_project_name = $("#projectNameInput").val();
            console.log(lesson_id + " " + new_project_name);
            if (new_project_name != "") {
                $.post("db.php", {
                        lesson_id: lesson_id,
                        project_name: new_project_name,
                        edit_project_name: "edit_project_name"
                    })
                    .done(function(data) {
                        alert(data);
                        getLessonByCourseIdGrid();
                        $(".defaultChecked").prop("checked", false);
                        $(".defaultDisabled").prop("disabled", true);
                    });
            }
        }


        // funtion

        function editProjectLink() {
            let lesson_id = $("#editLessonId").val();
            let new_project_link = $("#projectLinkInput").val();
            console.log(lesson_id + " " + new_project_link);

            if (new_project_link != "") {
                $.post("db.php", {
                        lesson_id: lesson_id,
                        project_link: new_project_link,
                        edit_project_link: "edit_project_link"
                    })
                    .done(function(data) {
                        alert(data);
                        getLessonByCourseIdGrid();
                        $(".defaultChecked").prop("checked", false);
                        $(".defaultDisabled").prop("disabled", true);
                    });
            }
        }

        function editTeacherDoc() {
            let lesson_id = $("#editLessonId").val();
            let new_teacher_doc = $("#teacherDocInput").val();
            console.log(lesson_id + " " + new_teacher_doc);

            if (new_teacher_doc != "") {
                $.post("db.php", {
                        lesson_id: lesson_id,
                        teacher_doc: new_teacher_doc,
                        edit_teacher_doc: "edit_teacher_doc"
                    })
                    .done(function(data) {
                        alert(data);
                        getLessonByCourseIdGrid();
                        $(".defaultChecked").prop("checked", false);
                        $(".defaultDisabled").prop("disabled", true);
                    });
            }
        }

        function editStudentDoc() {
            let lesson_id = $("#editLessonId").val();
            let new_student_doc = $("#studentDocInput").val();
            console.log(lesson_id + " " + new_student_doc);

            if (new_student_doc != "") {
                $.post("db.php", {
                        lesson_id: lesson_id,
                        student_doc: new_student_doc,
                        edit_student_doc: "edit_student_doc"
                    })
                    .done(function(data) {
                        alert(data);
                        getLessonByCourseIdGrid();
                        $(".defaultChecked").prop("checked", false);
                        $(".defaultDisabled").prop("disabled", true);
                    });
            }
        }



        function addlink(lesson_id, role) {
            console.log(lesson_id + role)
            var link = prompt(`lesson id: ${lesson_id}\nRole: ${role}`, "");
            if (link !== null) {
                if (link == "") {
                    alert("plase enter link")
                } else {
                    console.log(link);
                    $.ajaxSetup({
                        async: false
                    });
                    $.post("a.php", {
                            lesson_id: lesson_id,
                            link: link,
                            for: role,
                            action: "saveLink",
                        })
                        .done(function(resultData) {
                            // $("#response").html(resultData);
                            console.log(resultData)

                        });
                    getLinksByLessonIdGrid(lesson_id)
                }
            }
        }

        function editlink(lesson_id, link_id, link, role) {
            console.log(lesson_id + " " + role + " " + link_id + " " + link)
            var link = prompt(`lesson id: ${lesson_id}\nRole: ${role}`, "");
            if (link !== null) {
                if (link == "") {
                    alert("plase enter link")
                } else {
                    console.log(link);
                    $.ajaxSetup({
                        async: false
                    });
                    $.post("a.php", {
                            link_id: link_id,
                            link: link,
                            action: "updateLink",
                        })
                        .done(function(resultData) {
                            $("#response").html(resultData);
                            // console.log(resultData)

                        });
                    getLinksByLessonIdGrid(lesson_id)
                }
            }
        }

        // edit project desc
        $("#edit_project_desc").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('lesson_id', $("#editLessonId").val());
            formData.append('edit_project_desc', "edit_project_desc");

            console.log(formData);

            for (let [name, value] of formData) {
                console.log(`${name} = ${value}`); // key1 = value1, then key2 = value2
            }


            $.ajax({
                type: 'POST',
                url: "db.php",
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    alert(data);
                    getLessonByCourseIdGrid();
                    $(".defaultChecked").prop("checked", false);
                    $(".defaultDisabled").prop("disabled", true);
                }
            });


        });


        function deleteLink(lesson_id, link_id, role) {
            let linkId = link_id;
            console.log(link_id);
            console.log(lesson_id)
            var r = confirm("You Want Delete lesson!");

            if (r == true) {
                $.ajaxSetup({
                    async: false
                });
                $.post("a.php", {
                        link_id: linkId,
                        lesson_id: lesson_id,
                        for: role,
                        action: "deleteLink"
                    })
                    .done(function(data) {
                        $("#links_" + linkId).html(data);
                        console.log(data);
                    });
                getLinksByLessonIdGrid(lesson_id)
            }
        }

        function getLinksByLessonIdGrid(lesson_id) {
            console.log("call");
            $.post("a.php", {
                    lesson_id: lesson_id,
                    action: "getLinksByLessonIdGrid"
                })
                .done(function(data) {
                    // $('#frmLink')[0].reset();
                    // console.log(data);
                    $("#links_" + lesson_id).html(data);
                    // $("#linksGrid").html(data);
                    // $(this).add('<div></div>').html(data);
                });
        }


        function getLessonByCourseIdGrid() {
            let courseId = $("#courses").val();
            $.post("a.php", {
                    course_id: courseId,
                    action: "getLessonByCourseIdGrid"
                })
                .done(function(data) {
                    $("#lessonsGrid").html(data);
                });
            //            $.post("a.php", {course_id: courseId, action: "getLessonByCourseId"})
            //                .done(function (data) {
            //                     $("#lessons").html(data);
            //                });  
        }




        $(document).on('click', '.lesson_checkbox', function() {
            //alert($(this).prop('checked'));
            let lessonId = $(this).val();
            if ($(this).prop('checked') === true) {
                $.post("a.php", {
                        lesson_id: lessonId,
                        action: "getLinksByLessonIdGrid"
                    })
                    .done(function(data) {
                        $("#links_" + lessonId).html(data);
                        // $("#linksGrid").html(data);
                        // $(this).add('<div></div>').html(data);
                    });
            } else {
                $("#links_" + lessonId).html("");
            }
        });
        $("#frmLesson").toggle();
        $("#frmLink").toggle();
        $(document).on('click', '.form_checkbox', function() {
            $("#frmLesson").toggle();
        });
        $(document).on('click', '.form_link_checkbox', function() {
            $("#frmLink").toggle();

        });
        $(document).on('click', '.lesson_delete', function() {
            let lessonId = $(this).val();
            var r = confirm("You Want Delete lesson!");
            if (r == true) {
                let courseId = $("#courses").val();
                $.post("a.php", {
                        course_id: courseId,
                        lesson_id: lessonId,
                        action: "deleteLesson"
                    })
                    .done(function(data) {
                        console.log(data);
                        $("#links_" + lessonId).html(data);
                        // $("#linksGrid").html(data);
                        // $(this).add('<div></div>').html(data);
                        $(this).parent().remove();
                        getLessonByCourseIdGrid();
                    });

                //        $.post("a.php", {course_id: courseId, action: "getLessonByCourseIdGrid"})
                //                .done(function (data) {
                //                    $("#lessonsGrid").html(data);
                //                });  
            }
        });

        $(document).on('click', '.link_delete', function() {
            let linkId = $(this).val();
            var r = confirm("You Want Delete lesson!");
            if (r == true) {
                $.post("a.php", {
                        link_id: linkId,
                        action: "deleteLink"
                    })
                    .done(function(data) {
                        $("#links_" + linkId).html(data);
                        // $("#linksGrid").html(data);
                        // $(this).add('<div></div>').html(data);
                    });
                //              $(document).remove($("#link_" + linkId));
                $(this).parent().remove();

            }
        });

        $("#btnSaveSession").click(function() {
            //            
            let data = $('#frmSession').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            data.action = "saveSession";
            var date = new Date(data.sessionTime);
            // var date1 = new Date(+date+date.getTimezoneOffset()*60000)
            data.sessionTime = date.toISOString().slice(0, 19).replace('T', ' ');
            alert(date);

            $.post("a.php", data)
                .done(function(resultData) {
                    alert("" + resultData);
                    $("#response").html(resultData);
                });
        });

        //        $("#btnSaveLesson").click(function () {
        ////            
        //        var form = $("#frmLesson");
        //        var formData = new FormData(form[0]);
        //        formData.action = "saveLesson";
        //       
        //        $.post("a.php", formData)
        //                .done(function (resultData) {
        //                    alert("" + resultData);
        //                    $("#response").html(resultData);
        //                });
        //    });

        $("#frmLesson").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            let courseId = $("#courses").val();
            console.log(courseId);
            formData.append('course_id', $("#courses").val());

            console.log(formData);

            if (courseId != "") {
                $.ajax({
                    type: 'POST',
                    url: "a.php",
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        alert(data);
                        $('#frmLesson')[0].reset();
                        getLessonByCourseIdGrid();
                    }
                });
            } else {
                alert("Please Select Course First");
            }
        });





        $("#btnSaveLink").click(function() {
            //            
            let data = $('#frmLink').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            // alert($('input[type=checkbox]:checked').val());
            var lessonId = $('input[class=lesson_checkbox]:checked').val();

            if (lessonId != undefined) {
                data.lesson_id = lessonId;
                data.action = "saveLink";
                $.ajaxSetup({
                    async: false
                });
                $.post("a.php", data)
                    .done(function(resultData) {
                        $("#response").html(resultData);

                    });
                $.post("a.php", {
                        lesson_id: lessonId,
                        action: "getLinksByLessonIdGrid"
                    })
                    .done(function(data) {
                        alert("link added");
                        $('#frmLink')[0].reset();
                        console.log(data);
                        $("#links_" + lessonId).html(data);
                        // $("#linksGrid").html(data);
                        // $(this).add('<div></div>').html(data);
                    });
            } else {
                alert("Please Select Lesson First")
            }
        });
    </script>


</body>

</html>