<?php
date_default_timezone_set('Asia/Kolkata');
$mshost = getenv('MYSQL_Host');
$mspasswd = getenv('MYSQL_Pass');
$msdb = getenv('MYSQL_DB');
$msusr = getenv('MYSQL_User');
//$dbCon = new PDO(
//    sprintf('mysql:host=%s;dbname=%s', $mshost, $msdb),
//    $msusr,
//    $mspasswd,
//    array(
//        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_PERSISTENT => false
//    )
//);


 $dbCon = new PDO(
     'mysql:host=localhost;dbname=cg_new;charset=utf8mb4',
     'root',
     '',
     array(
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_PERSISTENT => false
     )
 );

$file_path = "https://admin.code-gurukul.com/uploads/";

//New commit

function getTeachers()
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from users where role="teacher"  order by first_name asc');
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    return $result;
}
function getStudents()
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from users where role="student" or role="teacher"  order by first_name asc');
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    return $result;
}

function getCourses()
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from courses');
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    return $result;
}

function getLessonsByCourseId($courseId)
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from lessons where course_id =:id order by sequence asc');
    $handle->bindParam(':id', $courseId, PDO::PARAM_INT);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    return $result;
}

function submitProjectLink($projectId, $link)
{
    global $dbCon;
    $handle = $dbCon->prepare('update schedules set project_submit_link=:link, project_submit_date=NOW() where schedule_id=:id');
    $handle->bindParam(':id', $projectId, PDO::PARAM_INT);
    $handle->bindParam(':link', $link, PDO::PARAM_STR); // explicitly tell pdo to expect an int
    $handle->execute();
    return 1;
}


function saveComments($projectId, $comments)
{
    global $dbCon;
    $handle = $dbCon->prepare('update schedules set teacher_comments=:comments where schedule_id=:id');
    $handle->bindParam(':id', $projectId, PDO::PARAM_INT);
    $handle->bindParam(':comments', $comments, PDO::PARAM_STR); // explicitly tell pdo to expect an int
    $handle->execute();
    return 1;
}

function mapStudentTeacher($data)
{
    global $dbCon;
    global $id;
    // $handle = $dbCon->prepare('select schedule_id from schedules');
    // $handle->execute();
    // $result = $handle->fetchAll(\PDO::FETCH_OBJ);
   
    // $handle = $dbCon->prepare('update users set teacher_id=:teacherId where user_id=:studentId');
    //  $handle = $dbCon->prepare('Insert into schedule_students ( student_id,schedule_id) values ("'. $data['student_id'].'")');
    // $handle->bindParam(':studentId', $data['student_id'], PDO::PARAM_INT);
    // $handle->bindParam(':teacherId', $data['teacher_id'], PDO::PARAM_INT); // explicitly tell pdo to expect an int
    $handle->execute();
    // values ("' .$data['student_id']. '")
    return 1;
}

function sessionMarkConducted($projectId)
{
    global $dbCon;
    $handle = $dbCon->prepare('update schedules set status="conducted" where schedule_id=:id');
    $handle->bindParam(':id', $projectId, PDO::PARAM_INT);
    $handle->execute();
    return 1;
}
function getUsers($role)
{
    global $dbCon;
    $handle = $dbCon->prepare('select user_id, first_name, last_name from users where role =  "' . $role . '"  order by first_name asc');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getUserDetails($userId)
{
    global $dbCon;
    $handle = $dbCon->prepare('select user_id, first_name, last_name from users where user_id =  "' . $userId . '"  order by first_name asc');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getSchedules($userId = null)
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from schedules
        left join lessons where schedules.lesson_id=lessons.lesson_id
    where user_id="' . $userId . '"');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getStudentsSchedules($userId = null)
{
    global $dbCon;
    $handle = $dbCon->prepare('select *,(schedule_time < NOW()) as expired , (schedule_time > (NOW() + INTERVAL 1 HOUR)) as upcoming,  (NOW() between (schedule_time - Interval 10 Minute) and (schedule_time + INTERVAL 1 HOUR)) as active, (NOW() > (schedule_time + INTERVAL 1 HOUR)) as `over`, (NOW() > (schedule_time + INTERVAL 30 Minute)) as half_hour_in_session  from schedules
        left join lessons on schedules.lesson_id=lessons.lesson_id
        left join users on schedules.teacher_id=users.user_id
    where student_id="' . $userId . '"'
        . ' order by schedule_time asc');
    //    $handle = $dbCon->prepare('select * from schedules
    //    where student_id="' . $userId . '"  
    //and schedule_time > (NOW() - INTERVAL 1 HOUR)
    //limit 1');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getScheduleDetails($sid)
{
    global $dbCon;
    $handle = $dbCon->prepare('select *, (NOW() > (schedule_time + INTERVAL 1 HOUR)) as over, (NOW() between (schedule_time - Interval 10 Minute) and (schedule_time + INTERVAL 1 HOUR)) as active from schedules
    left join lessons on schedules.lesson_id=lessons.lesson_id
    where schedule_id="' . $sid . '"');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    if ($result)
        return $result[0];
    return null;
}

function getTeachersSchedules($userId = null)
{
    global $dbCon;
    $handle = $dbCon->prepare('select *,(schedule_time < NOW()) as expired, (schedule_time > (NOW() + INTERVAL 1 HOUR)) as upcoming,  (NOW() between (schedule_time - Interval 10 Minute) and (schedule_time + INTERVAL 1 HOUR)) as active from schedules
    left join lessons on schedules.lesson_id=lessons.lesson_id
    left join users on schedules.student_id=users.user_id
    where schedules.teacher_id="' . $userId . '"'
        . ' order by schedule_time asc');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getAllSchedules()
{
    global $dbCon;
    $handle = $dbCon->prepare('select *,(schedule_time < NOW()) as expired , (schedule_time > (NOW() + INTERVAL 1 HOUR)) as upcoming, (schedule_time > (NOW() + INTERVAL 6 HOUR)) as validToCancel , (NOW() between (schedule_time - Interval 10 Minute) and (schedule_time + INTERVAL 1 HOUR)) as active, (NOW() > (schedule_time + INTERVAL 1 HOUR)) as `over` from schedules 
    left join lessons on schedules.lesson_id=lessons.lesson_id 
    left join users on schedules.teacher_id=users.user_id 
    left join schedule_students on schedules.schedule_id=schedule_students.schedule_id 
     order by schedule_time asc');
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    // schedule_students.student_id=users.user_id 
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getLinks($role = null)
{
    global $dbCon;
    $handle = $dbCon->prepare(
        'select *, (schedule_time > NOW()) as expired from schedules
    left join lessons on lessons.lesson_id=schedules.lesson_id
    left join links on lessons.lesson_id=links.lesson_id
    group by links.for'
    );
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getStudentsLinks($lesson_id)
{
    global $dbCon;
    $handle = $dbCon->prepare(
        'SELECT *
FROM `links`
left join lessons on lessons.lesson_id=links.lesson_id
WHERE `for` = "Student" AND links.lesson_id = ' . $lesson_id
    );
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getTeachersLinks($lesson_id)
{
    global $dbCon;
    $handle = $dbCon->prepare(
        'SELECT *
FROM `links`
left join lessons on lessons.lesson_id=links.lesson_id
WHERE `for` = "Teacher" AND links.lesson_id = ' . $lesson_id
    );
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function getLessonLinks($lesson_id)
{
    global $dbCon;
    $handle = $dbCon->prepare(
        'SELECT *
FROM `links`
left join lessons on lessons.lesson_id=links.lesson_id
WHERE links.lesson_id = ' . $lesson_id . ' ORDER BY `links`.`link_sequence` ASC'
    );
    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    //print_r($result);
    return $result;
}

function checkUser($username, $pass)
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from users
    where username="' . $username . '" and role="admin" or role ="operations" limit 1');

    //$handle = $dbCon->prepare('select * from users where role = "teacher"  ' );
    // $id = 7;
    // $handle->bindParam(':id', $id, PDO::PARAM_INT);
    $handle->execute();

    // Store the result into an object that we'll output later in our HTML
    // This object won't kill your memory because it fetches the data Just-In-Time to
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    // echo '<pre>';
    // return $result[0];

    if (isset($result[0]) &&  password_verify($pass, $result[0]->password)) {
        return $result[0];
    }
    return null;
}
// function checkStudentSession($data)
// {
//     global $dbCon;
//     $student_id = $data['student_id'];
//     $sessionTime = $data['sessionTime'];
//     $sql = "SELECT * from schedules where student_id = $student_id and schedule_time BETWEEN '$sessionTime'- INTERVAL 59 MINUTE and '$sessionTime' + INTERVAL 59 MINUTE";
//     $handle = $dbCon->prepare($sql);
//     $handle->execute();
//     $result = $handle->fetchAll(\PDO::FETCH_OBJ);

//     //print_r($result);

//     if (isset($result[0])) {
//         return $result;
//     }
//     return null;
// }
function checkTeacherSession($data)
{
    global $dbCon;
    $teacher_id = $data['teacher_id'];
    $sessionTime = $data['sessionTime'];
    $sql = "SELECT * from schedules where teacher_id = $teacher_id and schedule_time BETWEEN '$sessionTime'- INTERVAL 59 MINUTE and '$sessionTime' + INTERVAL 59 MINUTE";
    $handle = $dbCon->prepare($sql);

    $handle->execute();

    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    //print_r($result);

    if (isset($result[0])) {
        return $result;
    }
    return null;
}

function createSession($data)
{
    global $dbCon;
    global $id;
    //    echo 'Insert into schedules (lesson_id, teacher_id,student_id, schedule_time, room_id)
    //   values ("'.$data['lesson_id'].'","'.$data['teacher_id'].'","'.$data['student_id'].'","'.$data['sessionTime'].'","'.$data['room_id'].'")';
    //    exit;
//     $handle = $dbCon->prepare('Insert into schedules (lesson_id,teacher_id, schedule_time, room_id)
//    values ("' . $data['lesson_id'] . '","' . $data['teacher_id'] . '","' . $data['sessionTime'] . '","' . $data['room_id'] . '","")');
$handle = $dbCon->prepare('Insert into schedules (lesson_id,room_id,teacher_id, schedule_time) values ("' .$data['lesson_id']. '","'  .$data['room_id']. '","'  .$data['teacher_id']. '","'  .$data['sessionTime'].'")
       ');
    $result = $handle->execute();
    $id = $dbCon->lastInsertId();
    echo $id;
    foreach($data['student_id'] as $value )
    {
    $handles=$dbCon->prepare('Insert into schedule_students ( schedule_id,student_id) values ("'. $id.'","'  .$value. '")');
    $results=$handles->execute();
    }
    // print_r($result);
    // $handle1=$dbCon->prepare('Insert into schedule_students ( student_id) values ("'. $data['student_id'].'")');
    // $results=$handle1->execute();
    if ($result) {
        return $result;
    }
    return null;
}

function checkTeacherExists($data)
{
    global $dbCon;
    $handle = $dbCon->prepare('select * from users
    where email= "' . $data['email'] . '" limit 1');

    $handle->execute();

    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    //print_r($result);

    if (isset($result[0])) {
        return $result[0];
    }
    return null;
}

function createTeacher($data)
{
    global $dbCon;
    //    echo 'Insert into schedules (lesson_id, teacher_id,student_id, schedule_time, room_id)
    //   values ("'.$data['lesson_id'].'","'.$data['teacher_id'].'","'.$data['student_id'].'","'.$data['sessionTime'].'","'.$data['room_id'].'")';
    //    exit;
    $handle = $dbCon->prepare('Insert into users (first_name, last_name,username, password, email , mobile, registration_date, role)
   values ("' . $data['first_name'] . '","' . $data['last_name'] . '","' . $data['username'] . '","' . $data['password'] . '","' . $data['email'] . '","' . $data['mobile'] . '", NOW(), "teacher" )');

    $result = $handle->execute();


    // print_r($result);

    if ($result) {
        return $result;
    }
    return null;
}

function checkUsername($username)
{
    global $dbCon;
    $sql = "select count(*) as total from users WHERE username = '$username'";
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    if ($result[0]->total == 0) {
        echo 1;
    } else {
        echo 0;
    }
}

function checkEmail($email)
{
    global $dbCon;
    $sql = "select count(*) as total from users WHERE email = '$email'";
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    if ($result[0]->total == 0) {
        echo 1;
    } else {
        echo 0;
    }
}



function checkUsernameForEdit($username, $user_id)
{
    global $dbCon;
    $sql = "select count(*) as total from users WHERE username = '$username' and user_id != '$user_id'";
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    if ($result[0]->total == 0) {
        echo 1;
    } else {
        echo 0;
    }
}

function createUser($fname, $lname, $username, $email, $password, $role, $category)
{
    global $dbCon;
    $handle = $dbCon->prepare("INSERT INTO users (`first_name` ,`last_name` ,`username` ,`email`,`password`,`category` ,`role`,`registration_date`) VALUES ('$fname','$lname','$username','$email','$password','$category','$role',NOW())");
    $result = $handle->execute();
    print_r($result);
    if ($result) {
        return $result;
    }
    return null;
}

function createStudent($data)
{
    global $dbCon;
    //    echo 'Insert into schedules (lesson_id, teacher_id,student_id, schedule_time, room_id)
    //   values ("'.$data['lesson_id'].'","'.$data['teacher_id'].'","'.$data['student_id'].'","'.$data['sessionTime'].'","'.$data['room_id'].'")';
    //    exit;
    $handle = $dbCon->prepare('Insert into users (first_name, last_name,username, password, registration_date, role)
   values ("' . $data['first_name'] . '","' . $data['last_name'] . '","' . $data['first_name'] . '","' . $data['password'] . '", NOW(), "student" )');

    $result = $handle->execute();


    // print_r($result);

    if ($result) {
        return $result;
    }
    return null;
}
function createLesson($data)
{
    global $dbCon;
    $stmt = $dbCon->prepare("SELECT MAX(sequence) as last_sequence FROM `lessons` WHERE course_id = " . $data['course_id']);
    $stmt->execute();
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    $last_sequence = $r['last_sequence'];

    if ($last_sequence == null) {
        $last_sequence = 1;
    }
    $last_sequence = $last_sequence + 1;
    // echo $last_sequence;

    $handle = $dbCon->prepare('Insert into lessons (course_id, lesson_name,project_link, project_name, project_desc , teacher_doc, student_doc,sequence)
   values ("' . $data['course_id'] . '","' . $data['lesson_name'] . '","' . $data['link'] . '","' . $data['project_name'] . '","' . $data['project_description'] . '","' . $data['teacher_doc'] . '","' . $data['student_doc'] . '","' . $last_sequence . '")');

    $result = $handle->execute();


    // print_r($result);

    if ($result) {
        return $result;
    }
    return null;
}

function createLink($data)
{
    global $dbCon;

    $for = $data['for'];
    $link =  $data['link'];
    $lesson_id = $data['lesson_id'];

    $sql = "SELECT MAX(link_sequence) as last_sequence FROM `links` WHERE lesson_id = '$lesson_id' and `for`= '$for'";
    $stmt = $dbCon->prepare($sql);
    $stmt->execute();
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    $last_sequence = $r['last_sequence'];
    echo $last_sequence;
    $new_sequence = 1;
    if ($last_sequence != null) {
        $new_sequence =  $last_sequence + 1;
    }

    $sql1 = "Insert into links (`for`, link, lesson_id,link_sequence) values('$for','$link','$lesson_id',$new_sequence)";
    $handle = $dbCon->prepare($sql1);
    $result = $handle->execute();
    // print_r($result);

    if ($result) {
        return $result;
    }
    return null;
}
function deleteLesson($data)
{
    global $dbCon;
    $handle = $dbCon->prepare('Delete from lessons WHERE lesson_id = ' . $data['lesson_id']);
    $result = $handle->execute();
    $handle1 = $dbCon->prepare('select lesson_id, sequence from lessons WHERE course_id =' . $data['course_id'] . ' ORDER BY `lessons`.`sequence` ASC');
    $handle1->execute();
    $lesson_data = $handle1->fetchAll(\PDO::FETCH_OBJ);
    $count = 0;
    foreach ($lesson_data as $lesson) {
        $count = $count + 1;
        $sql = "UPDATE `lessons` SET `sequence` = $count WHERE `lessons`.`lesson_id` = $lesson->lesson_id";
        $handle = $dbCon->prepare($sql);
        $handle->execute();
    }


    if ($result) {
        return $result;
    }
    return null;
}
function deleteLink($data)
{
    global $dbCon;
    $handle = $dbCon->prepare('Delete from links WHERE link_id = ' . $data['link_id']);
    $result = $handle->execute();

    $lesson_id  = $data['lesson_id'];
    $for = $data['for'];

    $handle1 = $dbCon->prepare("select link_id, link_sequence from links WHERE lesson_id ='$lesson_id' AND `for`='$for' ORDER BY `links`.`link_sequence` ASC");
    $handle1->execute();
    $link_data = $handle1->fetchAll(\PDO::FETCH_OBJ);
    $count = 0;
    print_r($link_data);
    foreach ($link_data as $link) {
        $count = $count + 1;
        $sql = "UPDATE `links` SET `link_sequence` = $count WHERE `links`.`link_id` = $link->link_id";
        $handle = $dbCon->prepare($sql);
        $handle->execute();
    }

    // print_r($result);

    if ($result) {
        return $result;
    }
    return null;
}


function updateLink($data)
{
    global $dbCon;
    $handle = $dbCon->prepare('update links set link ="' . $data['link'] . '"  WHERE link_id = ' . $data['link_id']);

    $result = $handle->execute();


    // print_r($result);

    if ($result) {
        return $result;
    }
    return null;
}

function updateLessonSequence($data)
{
    $lesson_id = $data['lesson_id'];
    $sequence = $data['sequence'];
    global $dbCon;
    $handle = $dbCon->prepare('update lessons set sequence=' . $data['sequence'] . ' where lesson_id=' . $data['lesson_id']);

    $handle->execute();
    return 1;
}



// sorting

if (isset($_POST['getLessonData'])) {
    global $dbCon;
    $course_id = $_POST['course_id'];
    $sql = "select * from lessons where course_id=$course_id ORDER BY `lessons`.`sequence` ASC";
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    header('Content-type: application/json');
    echo json_encode($result);
}

if (isset($_POST['getLessonLinks'])) {
    global $dbCon;
    $lesson_id = $_POST['lesson_id'];
    $role = $_POST['role'];
    $sql = "select * from links where lesson_id=$lesson_id and `for`='$role' ORDER BY `links`.`link_sequence` ASC ";
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    header('Content-type: application/json');
    echo json_encode($result);
    // echo $lesson_id . $role;
}

if (isset($_POST['setNewSeq'])) {
    global $dbCon;
    $seq =  explode(",", $_POST['newSeq']);
    $count = 0;
    foreach ($seq as $lesson_id) {
        $count = $count + 1;
        $sql = "UPDATE `lessons` SET `sequence` = $count WHERE `lessons`.`lesson_id` = $lesson_id";
        $handle = $dbCon->prepare($sql);
        $handle->execute();
    }
    echo "set new seq";
}

if (isset($_POST['setNewSeqLink'])) {
    global $dbCon;
    $seq =  explode(",", $_POST['newSeq']);
    $count = 0;
    foreach ($seq as $link_id) {
        $count = $count + 1;
        $sql = "UPDATE `links` SET `link_sequence` = $count WHERE `link_id` = $link_id";
        $handle = $dbCon->prepare($sql);
        $handle->execute();
        // echo $link_id . " " . $count;
    }
    echo "done";
}



if (isset($_POST['getMax'])) {
    global $dbCon;
    $stmt = $dbCon->prepare("SELECT MAX(sequence) as last_sequence FROM `lessons` WHERE course_id = 1");
    $stmt->execute();
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    $last_sequence = $r['last_sequence'];
    if ($last_sequence == null) {
        $last_sequence = 0;
    }
    echo $last_sequence;
}




if (isset($_POST['save_project_link'])) {
    global $dbCon;
    $sql = 'update lessons set project_desc="' . $_POST["project_link"] . '"where lesson_id=' . $_POST["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}


if (isset($_POST['change_password'])) {
    global $dbCon;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = 'update users set password="' . $password . '"where user_id=' . $_POST["user_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}
//left join lessons on schedules.lesson_id=lessons.lesson_id

if (isset($_POST['get_session'])) {
    global $dbCon;
    $date = $_POST['date'];
   
    $sql = 'select *,(schedule_time < NOW()) as expired , (schedule_time > (NOW() + INTERVAL 1 HOUR)) as upcoming, (schedule_time > (NOW() + INTERVAL 6 HOUR)) as validToCancel , (NOW() between (schedule_time - Interval 10 Minute) and (schedule_time + INTERVAL 1 HOUR)) as active, (NOW() > (schedule_time + INTERVAL 1 HOUR)) as `over` from schedules 
    left join lessons on schedules.lesson_id=lessons.lesson_id 
    left join users on schedules.teacher_id=users.user_id 
    left join schedule_students on schedules.schedule_id=schedule_students.schedule_id 
   
   

      WHERE CAST(schedules.schedule_time AS DATE) = CAST("' . $date . '" AS DATE)';
  

   

   
   
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    // print_r($result);
    echo '
    <div class="row text-center">
        <div class="col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="emailRadio" value="0" checked>
            <label class="form-check-label" for="emailRadio">
                <b>Teacher</b>
            </label>
        </div>
        <div class="col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="usernameRadio" value="1">
            <label class="form-check-label" for="usernameRadio">
                <b>Student</b>
            </label>
        </div>
    </div>


    <div class="form-group">
        <input type="text" id="searchName" placeholder="Enter" class="form-control" onkeyup="search()">
    </div>
    <table class="table" id="allSchedules">
  <thead>
    <tr>
      <th scope="col">Teacher Name</th>
      <th scope="col">Student Name</th>
      <th scope="col">Lesson Name</th>
      <th scope="col">Time</th>
       <th scope="col">
        
       </th>
    </tr>
  </thead>
  <tbody>';
        // print_r($result);
        // die;
        // $result1 = json_decode(json_encode($result),true);
        // print_r($result1);
        // die;
    foreach ($result as $s) {
        // print_r($s);
        // $c=json_decode($s,true);
    //     print_r($s);
    // die;    
        $teacher_name = "";
        $student_name = "";
        // foreach (getUserDetails($s->teacherId) as $a) {
            $teacher_name = $s->first_name . " " . $s->last_name;
        // }

        foreach (getUserDetails($s->student_id) as $b) {
            $student_name = $b->first_name . " " . $b->last_name;
        }
        echo '
        <tr>
            <td>' . $teacher_name .  '</td>
            <td>' . $student_name . '</td>
            <td>' . $s->lesson_name . '</td>
            <td>' . $s->schedule_time . '</td>
            <td>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteSchedule(\'' . $s->schedule_id . '\',\'byDate\')" >D</button>
                    <button type="button" class="btn btn-sm btn-primary" 
                    onclick="editSchedule(\'' . $s->schedule_id . '\',\'byDate\')"
                    data-toggle="modal" data-target="#editScheduleModal">E</button>
                </div>
            </td>
        </tr>
        ';
    }

    echo "
    </tbody>
   </table>
    ";
}

if (isset($_POST['get_all_session'])) {
    $allSchedules = getAllSchedules();
    // print_r($allSchedules);
    // die;
    echo "<pre>";
    // print_r($allSchedules);
    echo "</pre>";
    echo '


    <div class="row text-center">
        <div class="col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="emailRadio" value="0" checked>
            <label class="form-check-label" for="emailRadio">
                <b>Teacher</b>
            </label>
        </div>
        <div class="col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="usernameRadio" value="1">
            <label class="form-check-label" for="usernameRadio">
                <b>Student</b>
            </label>
        </div>
    </div>
    <div class="form-group">
        <input type="text" id="searchName" placeholder="Enter" class="form-control" onkeyup="search()">
    </div>
    
    
    <table class="table" id="allSchedules">
  <thead>
    <tr>
      <th scope="col">Teacher Name</th>
      <th scope="col">Student Name</th>
      <th scope="col">Lesson Name</th>
      <th scope="col">Time</th>
       <th scope="col">
        
       </th>
    </tr>
  </thead>
  <tbody>';

    foreach ($allSchedules as $s) {
        // print_r($s);
        // die;
        if ($s->expired == 0) {
            $teacher_name = "";
            $student_name = "";
           foreach (getUserDetails($s->student_id) as $b) {
                $student_name = $b->first_name . " " . $b->last_name;
            }

            // foreach (getUserDetails($s->student_id) as $b) {
            //     $student_name = $b->first_name . " " . $b->last_name;
            // }
             $teacher_name = $s->first_name . " " . $s->last_name;
        

            echo '
        <tr>
            <td>' . $teacher_name .  '</td>
            <td>' . $student_name . '</td>
            <td>' . $s->lesson_name . '</td>
            <td>' . $s->schedule_time . '</td>
            <td>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteSchedule(\'' . $s->schedule_id . '\',\'all\')" >D</button>
                    <button type="button" class="btn btn-sm btn-primary" 
                    onclick="editSchedule(\'' . $s->schedule_id . '\',\'all\')"
                    data-toggle="modal" data-target="#editScheduleModal">E</button>
                </div>
            </td>
        </tr>
        ';
        }
    }

    echo "
    </tbody>
   </table>
    ";
}



// edit teacher

if (isset($_POST['edit_lesson_name'])) {
    global $dbCon;
    $sql = 'update lessons set lesson_name="' . $_POST["lesson_name"] . '"where lesson_id=' . $_POST["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}


if (isset($_POST['edit_project_name'])) {
    global $dbCon;
    $sql = 'update lessons set project_name="' . $_POST["project_name"] . '"where lesson_id=' . $_POST["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}

if (isset($_POST['edit_project_link'])) {
    global $dbCon;
    $sql = 'update lessons set project_link="' . $_POST["project_link"] . '"where lesson_id=' . $_POST["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}



if (isset($_POST['edit_project_desc'])) {
    global $dbCon;

    $data = $_POST;

    // project desc
    if (0 < $_FILES['new_project_description']['error']) {
        // echo 'Error: ' . $_FILES['teacher_doc']['error'] . '<br>';
        $data['new_project_description'] = "";
    } else {
        $project_description = $_FILES["new_project_description"]["name"];
        move_uploaded_file($_FILES['new_project_description']['tmp_name'], 'uploads/' . $project_description);
        $data['new_project_description'] = $project_description;
    }

    $sql = 'update lessons set project_desc="' . $data['new_project_description'] . '"where lesson_id=' . $data["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}


if (isset($_POST['edit_student_doc'])) {
    global $dbCon;
    $sql = 'update lessons set student_doc="' . $_POST["student_doc"] . '"where lesson_id=' . $_POST["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}


if (isset($_POST['edit_teacher_doc'])) {
    global $dbCon;
    $sql = 'update lessons set teacher_doc="' . $_POST["teacher_doc"] . '"where lesson_id=' . $_POST["lesson_id"] . ';';
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "Save";
}


// update user data
function updateUserDetail($key, $value, $user_id)
{
    global $dbCon;
    $sql = "update users set $key = '$value' where user_id = $user_id ";
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    // echo "Save";
}


function updateScheduleDetail($key, $value, $schedule_id)
{
    global $dbCon;
    $sql = "update schedules set $key = '$value' where schedule_id = $schedule_id ";
    // echo $sql;
    $handle = $dbCon->prepare($sql);
    $handle->execute();
}

function deleteSchedules($schedule_id)
{
    global $dbCon;
    $sql = "delete from schedules where schedule_id = $schedule_id";
    $handle = $dbCon->prepare($sql);
    $handle->execute();
    echo "scheduled deleted";
}



// updateUserDetail("first_name", "alokshete", 143);
