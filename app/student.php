
<?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
session_start();
include_once("db.php");
// $teachers = getUsers("teacher");
// $students = getUsers("student");
$lesson_id = $_GET['lid'];
$schedule_id = $_GET['sid'];
if(empty($lesson_id)){
header("Location: schedule.php");
}
$schedule = getScheduleDetails($schedule_id);
$schedules = getStudentsSchedules($_SESSION['user_id']);
$studentsLinks = getStudentsLinks($lesson_id);
//print_r($links);
//print_r($_SESSION);
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
  </head>
  <style>
        .navbar{
      font-size: 12px;
    }
      .table td, .table th {
    
    font-size: 12px;
}
.btn {
    font-size: 12px;
}
  </style>
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
            <a class="nav-link" target="_blank" href="schedule.php"  tabindex="-1" data-toggle="tooltip" data-placement="bottom" title="This will open dashboard in new tab" >My Dashboard</a>

</span>

    <span class="nav-item float-right">
        <a class="nav-link float-right" href="#" tabindex="-1" >Welcome <?= $username ?> <?= '| ( '. ucfirst($role) .')' ?></a>
</span>
    <span class="nav-item float-right">
      <?php
      if($loggedIn){
          echo '
        
          <a class="nav-link" href="logout.php" tabindex="-1" >Logout</a>
       ';
      }
      ?>
      </span>
    <button class="btn btn-primary my-2 my-sm-0" type="submit">Book Free Trial (Sign Up)</button>
    <!-- <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Book Free Trial</button>
    </form> -->
  </div>
</nav>
  <div class="container-fluid">
  <div class="row">
    <div class="col">
    <div id="meet">
    </div>
    
    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
    var room_id = "<?= $_GET['join'] ?>" ;
    //alert (room_id);
    </script>
        <script>
            var domain = "meet.jit.si";
            var options = {
                roomName: room_id,
                width: 700,
                height: 420,
                parentNode: meet,
                // configOverwrite: {},
                // interfaceConfigOverwrite: {
                //     filmStripOnly: true
                // }
            }
            var api = new JitsiMeetExternalAPI(domain, options);
        </script>
        
    </div>
    <div class="col">
    Lesson: <?= $schedule->lesson_name ?> <br>   
    Students's Activities &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<!--    <a class="btn btn-outline-success" target="_blank" href=" //$schedule->student_doc ?>" > Lesson Document</a>-->
<table class="table">
  
<?php foreach($studentsLinks as $key=>$links): 
    $key = $key + 1; ?>
    <tr>
      <th scope="row"><?= $key ?></th>
      <td><a href="<?= $links->link ?>" target="_blank">  Activity <?= $key ?></a></td>
     
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php if ($schedule->project_link ) {
    if($schedule->over){
        echo 'Project Link: <a class="btn btn-outline-success" target="_blank" href="'.$schedule->project_link.'" \'> Link </a>' ;
    }
} else {
    echo 'No Project';
}
 ?>
<div class="h1 primary" style="color:red;" id="reward"> </div>
    <div class="h1 primary" style="color:red;" id="messages"> </div>
    <div class="h1 primary" style="color:red;" id="list"> </div>
    </div>
    
  </div>
  
    
</div>
<!-- <footer class="footer">
  <div class="container">
  <div class="row">

    <span class="text-muted">All Rights Reserved. @ Code-Gurukul.com </span>
    </div>
  </div>
</footer> -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.2.0/dist/confetti.browser.min.js"></script>
    <script>
    // do this for 30 seconds
// var duration = 30 * 1000;
// var end = Date.now() + duration;

// (function frame() {
//   // launch a few confetti from the left edge
//   confetti({
//     particleCount: 7,
//     angle: 60,
//     spread: 55,
//     origin: { x: 0 }
//   });
//   // and launch a few from the right edge
//   confetti({
//     particleCount: 7,
//     angle: 120,
//     spread: 55,
//     origin: { x: 1 }
//   });

//   // keep going until we are out of time
//   if (Date.now() < end) {
//     requestAnimationFrame(frame);
//   }
// }());
function triggerEvent(){
  $(document).get( "sendEvent.php" );
}
// const evtSource = new EventSource("sendEvent.php");
// evtSource.addEventListener("message", function(event) {
//   bash();
//   //event.target.close();
  
//   // const newElement = document.createElement("li");
//   // const time = JSON.parse(event.data).time;
//   // newElement.innerHTML = "ping at " + time;
//   // document.getElementById("messages").appendChild(newElement);
// });

function bash(){
  document.getElementById("reward").innerHTML = 10;
  //alert(Math.random());
confetti({
  particleCount: 100,
  startVelocity: 30,
  spread: 360,
  origin: {
    //x: Math.random(),
    x: .700,
    // since they fall down, start a bit higher than random
    //y: Math.random() - 0.2
    y: .500
  }
});
setTimeout(() => {
  document.getElementById("reward").innerHTML =" ";
}, 1000);
}
    </script>  
    <script>

// evtSource.addEventListener("ping", function(event) {
//   const newElement = document.createElement("li");
//   const time = JSON.parse(event.data).time;
//   newElement.innerHTML = "ping at " + time;
//   eventList.appendChild(newElement);
// });
</script>  
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('fa962549770c09ab2939', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      //alert(JSON.stringify(data));
      bash();
    });
  </script>    
  </body>
</html>
