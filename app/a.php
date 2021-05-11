<?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
include_once("db.php");
$action = ($_POST['action']) ? $_POST['action'] : null;
if ($action == 'getLessonByCourseId') {
    $courseId = $_POST['course_id'];
    $lessons = getLessonsByCourseId($courseId);
    //print_r($response);
    //echo '<select name=lessons id=lessons>';
    foreach ($lessons as $lesson) {
        echo "<option value=$lesson->lesson_id > $lesson->lesson_name </option>";
    }
    //echo '</select>';
}
if ($action == 'getLessonByCourseIdGrid') {
    $courseId = $_POST['course_id'];
    $lessons = getLessonsByCourseId($courseId);
    // print_r($lessons);
    echo '<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">No</th>
      <th scope="col">Lesson</th>

      <th scope="col">Project Name</th>
      <th scope="col">Project Desc</th>
      <th scope="col">Project Link</th>
      <th scope="col">Teacher Doc</th>
      <th scope="col">Student Doc </th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody><div name=lessons id=lessons>';
    foreach ($lessons as $lesson) {
        echo "<tr id=tr_$lesson->lesson_id draggable=true > <td id=lesson_$lesson->lesson_id> <input class=lesson_checkbox type=checkbox name=$lesson->lesson_id value=$lesson->lesson_id> </input></td> "
            . "<td>$lesson->sequence </td>"
            . "<td>$lesson->lesson_name </td>"
            . "<td>$lesson->project_name </td> ";

        if (strlen($lesson->project_desc) == 0) {
            echo "<td></td>";
        } else {
            echo "<td> <button type='button' class='btn btn-outline-info' data-toggle='modal' data-target='#showProjectDocModal' onclick='showProjectDoc(\"$file_path\",\"$lesson->project_desc\")' >$lesson->project_desc</button> </td>";
        }

        if (strlen($lesson->project_link) == 0) {
            echo "<td></td>";
        } else {
            echo "<td><a class='btn btn-outline-success' target='_blank' href='$lesson->project_link' >Project Link</a></td>";
        }

        if (strlen($lesson->teacher_doc) == 0) {
            echo "<td></td>";
        } else {
            echo "<td><a class='btn btn-outline-success' target='_blank' href='$lesson->teacher_doc' >Teacher Doc</a></td>";
        }

        if (strlen($lesson->student_doc) == 0) {
            echo "<td></td>";
        } else {
            echo "<td><a class='btn btn-outline-success' target='_blank' href='$lesson->student_doc' >Student Doc</a></td>";
        }

        echo "<td> 
             <div class='btn-group role='group' aria-label='Basic example'>
            <button class='lesson_delete btn btn-danger' name=D value=$lesson->lesson_id > D </button>
            <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#editLessonModal' onclick='editLesson($lesson->lesson_id, \"$lesson->lesson_name\", \"$lesson->project_name\", \"$lesson->project_link\",\"$lesson->teacher_doc\",\"$lesson->student_doc\")'>E</button>
            </div>
            </td>
             </tr>"
            . "<tr><td colspan='9'><span id=links_$lesson->lesson_id></span></td></tr>";
    }
    echo "</div></tbody>
</table>

";
}

if ($action == 'getLinksByLessonIdGrid') {
    $lessonId = $_POST['lesson_id'];
    $links = getLessonLinks($lessonId);
    // print_r($lessons);
    echo '<div class="row g-0 m-0"><div class= "col-sm g-0 m-0">';
    // student table
    echo '
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">
                    <div class="row">
                        <div class="col-sm">
                            <b>
                            Student Links
                            </b>
                          </div>
                        <div class="col-sm">
                            <button type="button" class="btn btn-success" onclick=addlink(' . $lessonId . ',"student") >Add Student Link</button>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th scope="col" width="10px">#</th>
                <th scope="col">Link</th>
                <th scope="col" class="text-right" >Action</th>
            </tr>
        </thead>
    ';
    // stuent table main data
    $count = 0;
    foreach ($links as $link) {
        if (strtolower($link->for) == 'student') {
            $count = $count + 1;
            echo "<tr> <td>$count</td> <td> <a target='_blank' href=$link->link> $link->link </a></td>
             <td class='text-right'>
             <div class='btn-group role='group' aria-label='Basic example'>
                <button class=' btn btn-danger' name=D onclick =deleteLink(" . $lessonId . "," . $link->link_id . ",'student') > D </button>
                <button class=' btn btn-primary' name=E onclick=editlink(" . $lessonId . "," . $link->link_id . ",'" . $link->link . "','Student') > E </button>
             </div>   
             </td></tr>";
        }
    }
    // stuent table main data end 

    echo '</div></tbody></table>';
    // student table end

    echo '</div><div class= "col-sm">';

    // teacher table
    echo '
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">
                    <div class="row">
                        <div class="col-sm">
                            <b>
                            Teacher Links
                            </b>
                          </div>
                        <div class="col-sm">
                            <button type="button" class="btn btn-success" onclick=addlink(' . $lessonId . ',"teacher") >Add Teacher Link</button>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
            <th scope="col" width="10px">#</th>
            <th scope="col" >Link</th>
            <th scope="col" class="text-right">Action</th>
            </tr>
        </thead>
    ';
    $count = 0;
    // teacher table main data
    foreach ($links as $link) {
        if (strtolower($link->for) == 'teacher') {
            $count = $count + 1;
            echo "<tr> <td>$count</td> <td> <a target='_blank' href=$link->link> $link->link </a></td>
             <td class='text-right'>
              <div class='btn-group role='group' aria-label='Basic example'>
                <button class=' btn btn-danger' name=D onclick =deleteLink(" . $lessonId . "," . $link->link_id . ",'teacher') > D </button>
                <button class='link_edit btn btn-primary' onclick=editlink(" . $lessonId . "," . $link->link_id . ",'" . $link->link . "','Teacher') > E </button>
             </div>
                </td></tr>";
        }
    }
    // teacher table main data end 

    echo '</div></tbody></table>';
    // teacher table end

    echo  '</div></div>';
}

if ($action == 'saveSession') {
    print_r($_POST);
    // $tz = new DateTimeZone('Asia/Kolkata');

    // $date = new DateTime($_POST['sessionTime']);
    // $date->setTimezone($tz);
    // //echo $date->format('Y-m-d H:i:s');
    // $_POST['sessionTime'] = $date->format('Y-m-d H:i:s');
    //exit;
    date_default_timezone_set('Asia/Kolkata');

    $_POST['sessionTime'] = date('Y-m-d H:i:s', strtotime($_POST['sessionTime']));
    // echo 'date.timezone: ' . ini_get('date.timezone');
    // echo "\n" . $_POST['sessionTime'];
    $data = $_POST;
    $session = checkTeacherSession($data);
    if ($session) {
        echo 'Teacher session already exist ';
        foreach ($session as $v) {
            echo "Session Time : " . $v->schedule_time . "\n";
        }
        exit;
    }
    // $session = checkStudentSession($data);
    // if ($session) {
    //     echo 'Student session already exist ';
    //     foreach ($session as $v) {
    //         echo "Session Time : " . $v->schedule_time . "\n";
    //     }
    //     exit;
    // }
    $session = createSession($data);
 
    if ($session) {
        echo 'session created';

        exit;
    }
    echo 'session creation failed';
}

if ($action == 'generatePassword') {
    echo password_hash($_POST['p'], PASSWORD_DEFAULT);
}

if ($action == 'saveTeacher') {
    //print_r($_POST);
    //exit;
    $data = $_POST;
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $data['role'] = 'teacher';
    $teacher = checkTeacherExists($data);
    if ($teacher) {
        echo 'Teacher already exist';
        exit;
    }

    $teacher = createTeacher($data);
    if ($teacher) {
        echo 'Registered successfully';
        exit;
    }
    echo 'teacher registration failed';
}


if ($action == 'saveUser') {

    $fname = ucwords(strtolower($_POST['fname']));
    $lname = ucwords(strtolower($_POST['lname']));
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $category = $_POST['category'];
    $user = createUser($fname, $lname, $username, $email, $password, $role, $category);
    if ($user) {
        echo 'User created successfully';
        exit;
    }
    echo 'User creation failed';
}

if ($action == 'checkUsernameForEdit') {
    $username = $_POST['username'];
    $user_id = $_POST['user_id'];
    checkUsernameForEdit($username, $user_id);
}

if ($action == 'checkUsername') {
    $username = $_POST['username'];
    checkUsername($username);
}

if ($action == 'checkEmail') {
    $email = $_POST['email'];
    checkEmail($email);
}

if ($action == 'mapStudentTeacher') {

    $data = $_POST;
    $result = mapStudentTeacher($data);
    if ($result) {
        // echo "mapping done";
        echo $result;
    } else {
        echo "mapping fail";
    }
}

if ($action == 'saveStudent') {
    //print_r($_POST);
    //exit;
    $data = $_POST;
    $data['password'] = password_hash($data['first_name'], PASSWORD_DEFAULT);
    $data['role'] = 'student';
    //    $student = checkStudentExists($data);
    //    if ($student) {
    //        echo 'Student already exist';
    //        exit;
    //    }

    $student = createStudent($data);
    if ($student) {
        echo 'Student created successfully';
        exit;
    }
    echo 'Student creation failed';
}

if ($action == 'saveLesson') {
    $data = $_POST;

    // project desc
    if (0 < $_FILES['project_description']['error']) {
        // echo 'Error: ' . $_FILES['teacher_doc']['error'] . '<br>';
        $data['project_description'] = "";
    } else {
        $project_description = $_FILES["project_description"]["name"];
        move_uploaded_file($_FILES['project_description']['tmp_name'], 'uploads/' . $project_description);
        $data['project_description'] = $project_description;
    }

    // teacher doc
    // if (0 < $_FILES['teacher_doc']['error']) {
    //     // echo 'Error: ' . $_FILES['teacher_doc']['error'] . '<br>';
    //     $data['teacher_doc'] = "";
    // } else {
    //     $teacher_doc = $data['course_id'] . "_" . $data['lesson_name'] . "_TD_" . uniqid() . "."
    //         . pathinfo($_FILES["teacher_doc"]["name"], PATHINFO_EXTENSION);
    //     move_uploaded_file($_FILES['teacher_doc']['tmp_name'], 'uploads/' . $teacher_doc);
    //     $data['teacher_doc'] = $_FILES['teacher_doc']['name'];
    // }
    // teacher Doc
    // if (0 < $_FILES['Student_doc']['error']) {
    //     $data['student_doc'] = "";
    //     // echo 'Error: ' . $_FILES['Student_doc']['error'] . '<br>';
    // } else {
    //     $student_doc = $data['course_id'] . "_" . $data['lesson_name'] . "_SD_" . uniqid() . "."
    //         . pathinfo($_FILES["Student_doc"]["name"], PATHINFO_EXTENSION);
    //     move_uploaded_file($_FILES['Student_doc']['tmp_name'], 'uploads/' . $student_doc);
    //     $data['student_doc'] = $_FILES['Student_doc']['name'];
    // }
    $lesson = createLesson($data);
    if ($lesson) {
        echo 'Lesson Created successfully';
        exit;
    }
    echo 'lesson creation  failed';
}

if ($action == 'saveLink') {
    $data = $_POST;

    $lesson = createLink($data);
    if ($lesson) {
        echo 'Created successfully';
        exit;
    }
    // echo ($lesson);
    echo 'Link creation  failed';
}

if ($action == 'deleteLesson') {
    $data = $_POST;

    $lesson = deleteLesson($data);
    if ($lesson) {
        echo 'delete successfully';
        exit;
    }
    echo 'lesson delete  failed';
}
if ($action == 'deleteLink') {
    $data = $_POST;

    $link = deleteLink($data);
    // echo $link;
    if ($link) {
        echo 'delete successfully';
        exit;
    }

    echo 'Link delete  failed';
}

if ($action == 'updateLink') {
    $data = $_POST;

    $link = updateLink($data);
    if ($link) {
        echo 'delete successfully';
        exit;
    }
    echo 'Link delete  failed';
}

if ($action == 'arrangeLessons') {
    $data = $_POST;

    $lesson = updateLessonSequence($data);
    if ($lesson) {
        echo 'done';
        exit;
    }
    echo 'lesson delete  failed';
}

if ($action == 'mapping') {
    $data = $_POST;

    $response = mapStudentTeacher($data);
    if ($response) {
        echo 'mapping done';
        exit;
    }
    echo 'mapping  failed';
}


if ($action == "edit_user_details") {
    $data = $_POST;
    unset($data["action"]);
    foreach ($data as $key => $val) {
        if ($key == "password") {
            if (strlen($val) != 0) {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
                updateUserDetail($key, $password, $data['user_id']);
                //                echo $password;
                //                echo "$val password";
            } else {
                // echo "password not updated";
            }
        } elseif ($key == "birthday_date") {
            if (strlen($val) != 0) {
                updateUserDetail($key, $val, $data['user_id']);
            }
        } else {
            updateUserDetail($key, $val, $data['user_id']);
        }
    }
    echo "User Data Updated";
}






if ($action == 'updateScheduleLessonForm') {
    $data = $_POST;
    updateScheduleDetail('lesson_id', $data['lesson_id'], $data['schedule_id']);
    echo "schedule lesson updated";
}

if ($action == 'updateScheduleTimeForm') {
    $data = $_POST;
    updateScheduleDetail('schedule_time', $data['schedule_time'], $data['schedule_id']);
    echo "schedule time updated";
}

if ($action == 'deleteSchedule') {
    $data = $_POST;
    deleteSchedules($data['schedule_id']);
}
