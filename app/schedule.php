<?php
session_start();
// ini_set("error_reporting", E_ALL);
// ini_set("display_errors", true);
include_once("db.php");
// $teachers = getUsers("teacher");
// $students = getUsers("student");
$_SESSION['role'] = 'teacher';
if ($_SESSION['role'] == 'teacher') {

    $schedules = getTeachersSchedules($_SESSION['user_id']);
} else {
   // $schedules = getStudentsSchedules($_SESSION['user_id']);
}
$schedules = getAllSchedules();
$students = getUsers('students');
$links = getLinks();

$username = "";
$loggedIn = false;
$role = "";
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
    $username = $_SESSION["username"];
    $role = $_SESSION["role"];
    $loggedIn = $_SESSION["loggedIn"];
    if ($role == "student") {
        //header("Location: student.php");
    }
} else {
    //header("Location: login.php");
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
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>


                </ul>
                <span class="nav-item float-right">
                    <a class="nav-link" target="_blank" href="schedule.php" tabindex="-1" >My Dashboard</a>
                </span>
                <span class="nav-item float-right">
                    <a class="nav-link" target="" href="#" tabindex="-1" >Help Center</a>
                </span>
                <span class="nav-item float-right">
                    <a class="nav-link float-right" href="#" tabindex="-1" >Welcome <?= $username ?> <?= '| ( ' . ucfirst($role) . ')' ?></a>
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
                <button class="btn btn-primary my-2 my-sm-0" type="submit">Book Free Trial (Sign Up)</button>
                <!-- <form class="form-inline my-2 my-lg-0">
                  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Book Free Trial / Sign Up</button>
                </form> -->
            </div>
        </nav>
        
        <div class="container-fluid">
            <div class="row">
                
                <div class="col">
                     <p class="text-center font-weight-bold"> My Sessions </p>
                    <?php
//   if($_SESSION['role'] == 'teacher'){
// foreach ($schedules as $schedule){
//   echo  '<button class="btn btn-outline-success my-2 my-sm-0"><a href="teacher.php?join='. $schedule->room_id .'"> Join </a></button>';
//   echo '<br>';
// }
// } else {
//     foreach ($schedules as $schedule){
//         echo  '<button class="btn btn-outline-success my-2 my-sm-0"><a href="student.php?join='. $schedule->room_id .'"> Join </a></button>';
//         echo '<br>';
//       }
// }
// foreach ($links as $link){
//   echo $link->for .' '. $link->lesson_name. ' ' . $link->link;
//   echo '<br>';
// }
                    ?>
                   
                    <table class="table">
                        <tr>
                            <th scope="row">Sr. No.</th>
                            <th>Lesson Name</th>
                            <th><?php if ($_SESSION['role'] == 'teacher') { echo 'Student';} else { echo 'Teacher'; } ?></th>
                            <th>Session Date Time</th>
                            <th>Actions</th>

                        </tr>
<?php if ($_SESSION['role'] == 'teacher'): ?>
    <?php foreach ($schedules as $key => $schedule):
        $key = $key + 1;
        ?>
                                <tr>
                                    <th scope="row"><?= $key ?></th>
                                    <td><?= $schedule->lesson_name; ?> <br><br>
                                        <a  class="btn btn-outline-success" my-2 my-sm-0" target="_blank" href="<?= $schedule->teacher_doc  ?>" > Teacher Doc</a>   
                                    <a class="btn btn-outline-success" target="_blank" href="<?= $schedule->student_doc ?>" > Student Doc</a>
                                    </td>
                                    <td><?= $schedule->first_name . ' '. $schedule->last_name; ?><br> (<?= $schedule->category ?>)</td>
                                    <td><?= $schedule->schedule_time; ?></td>
                                    <td>
                                         <?php if($schedule->active == 1): ?>
                                            <button id="teacherJoinButton" class="btn btn-success my-2 my-sm-0"><a style="color:white;" href="teacher.php?sid=<?= $schedule->schedule_id ?>&lid=<?= $schedule->lesson_id ?>&join=<?= $schedule->room_id; ?>"> Join Session </a></button>
                                            <br>
                                           <?php endif; ?>  
                                            <a  href="teacher.php?sid=<?= $schedule->schedule_id ?>&lid=<?= $schedule->lesson_id ?>&join=<?= $schedule->room_id; ?>"> View All Activities </a>
                                            <br>
                                        
                                        <?php if($schedule->expired == 1): ?>
                                             <?php if($schedule->status == 'conducted'): ?>
                                            Session Conducted <br>
                                            <?php   else: ?>
                                            <button class="btn btn-outline-success my-2 my-sm-0" onClick="sessionMarkConducted( <?= $schedule->schedule_id ?>, this)">Mark Conducted</button>
                                            <br>
                                                <?php  endif; ?>
                                            <?php if ($schedule->project_link ) {
                                            echo '<a  target="_blank" href="'.$schedule->project_link.'" \'> Project Link </a>' ;
                                        } else {
                                            echo 'No Project';
                                        }
                                         ?>
                                            <?php if($schedule->project_submit_link): ?>
                                            <br>
                                            
                                            <a  target="_blank" href="<?= $schedule->project_submit_link  ?>" > Submitted Project Link</a> ( <?= $schedule->project_submit_date  ?> )
                                            <br>Project Comments:
                                            <textarea style="text-align:left;" cols="50" rows="6" id="teacherComments_<?= $schedule->schedule_id ?>" class="input-group-text"> <?= $schedule->teacher_comments  ?></textarea> <br><button class="btn btn-outline-success my-2 my-sm-0" onClick="saveComments( <?= $schedule->schedule_id ?>, document.getElementById('teacherComments_<?= $schedule->schedule_id ?>').value )">Submit Comments</button>
                                        
                                             <?php  endif; ?>
                                        <?php   else: ?>
                                           
                                        <?php  endif; ?>
                                            
                                    </td>
                                </tr>
    <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ($_SESSION['role'] == 'student'): ?>
                            <?php foreach ($schedules as $key => $schedule):
                                $key = $key + 1;
                                ?>
                                <tr>
                                    <th scope="row"><?= $key ?></th>
                                    <td><?= $schedule->lesson_name; ?><br>
                                    <br>
                                       
                                    </td>
                                    <td><?= $schedule->first_name . ' '. $schedule->last_name; ?></td>
                                    <td><?= $schedule->schedule_time; ?></td>
                                    <td>
                                            <?php if($schedule->active == 1 and $schedule->status != 'conducted'): ?>
                                            <button id="studentJoinButton" class="btn btn-success my-2 my-sm-0"><a style="color:white;" href="student.php?sid=<?= $schedule->schedule_id ?>&lid=<?= $schedule->lesson_id ?>&join=<?= $schedule->room_id; ?>"> Join Session </a></button>
                                            <br>
                                            <?php if ($schedule->half_hour_in_session): ?>
                                            <br>
                                                     <a target="_blank" href="<?= $schedule->project_link ?>"> Project Link </a> 
                                                    <br>
                                            <?php  endif; ?>    
                                            <?php  endif; ?>
                                        <?php if($schedule->expired == 1): ?>
                                        <?php if ($schedule->project_link ): ?>
                                             <?php if ($schedule->over): ?>
                                                     <a class="btn btn-outline-success" target="_blank" href="<?= $schedule->student_doc ?>" >View Lesson Document</a><br><br>
                                                     <a target="_blank" href="<?= $schedule->project_link ?>"> Project Link </a> 
                                                    <br><br>
                                                    
                                            <input type="text" class="input-group-text"  id="projectSubmitLink_<?= $schedule->schedule_id ?>" placeholder="Completed Project Link" /> <br><button class="btn btn-outline-success my-2 my-sm-0" onClick="saveLink( <?= $schedule->schedule_id ?>, document.getElementById('projectSubmitLink_<?= $schedule->schedule_id ?>').value )">Submit</button>
                                            <?php if($schedule->project_submit_link): ?>
                                            <br>
                                            
                                            <a  target="_blank" href="<?= $schedule->project_submit_link  ?>" > Submitted Project Link</a> ( <?= $schedule->project_submit_date  ?> )
                                            
                                             <?php  endif; ?>
                                                <?php if ($schedule->teacher_comments): ?>
                                            <br>
                                            Teachers Comments:
                                            <p> <pre><?= $schedule->teacher_comments ?></pre></p>
                                             <?php  endif; ?> 
                                            
                                              <?php  endif; ?>  
                                        <?php   else: ?>
                                            No Project
                                        
                                         
                                         <?php  endif; ?>
                                         <?php   else: ?>
       
                                        <?php  endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    function saveLink(id, link){
$.post( "submitproject.php", { schedule_id: id, project_submit_link: link, action:"saveLink" })
  .done(function( data ) {
    alert( "" + data );
  });
    }
    
        function saveComments(id, comments){
$.post( "submitproject.php", { schedule_id: id, comments: comments, action:"saveComments" })
  .done(function( data ) {
    alert( "" + data );
  });
    }
        function sessionMarkConducted(id, el){
$.post( "submitproject.php", { schedule_id: id, action: "sessionMarkConducted"})
  .done(function( data ) {
    alert( "" + data );
    $(el).prop('visible', false);
    $("#studentJoinButton").css("visibility", "hidden");
    $("#teacherJoinButton").css("visibility", "hidden");
    $("#studentJoinButton").remove();
    $("#teacherJoinButton").remove();
    $(el).replaceWith("Session Conducted");
  });
    }
</script>
    </body>
</html>