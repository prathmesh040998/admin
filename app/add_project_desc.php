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
<div class="container mt-5">
    <div class="container">
        <div class="row">

            <div class="col-sm-6">

                <form id="frmAddProjectLink">
                    <p class="text-center font-weight-bold"> </p>
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
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Lessons</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="lesson_id" id="lessons" required>
                                    <option value="">First select Course</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Project Link</label>
                            <div class="col-sm-10">
                                <input type="text" name="project_link" class="form-control" id="project_link" placeholder="Project link" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto my-1">
                        <button id="btnSaveProjectLink" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <body>
        <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
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
            $("#frmAddProjectLink").submit(function(event) {
                event.preventDefault();
                var data = $('#frmAddProjectLink').serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});

                console.log(data);

                $.post("db.php", {
                        course_id: data.course_id,
                        lesson_id: data.lesson_id,
                        project_link: data.project_link,
                        save_project_link: "save_project_link"
                    })
                    .done(function(data) {
                        alert(data);
                        $("#project_link").val('');
                    });


            });
        </script>
    </body>

</html>