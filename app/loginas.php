    <?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
include_once("db.php");
session_start();
//print_r($_SESSION);
$username = "";
$loggedIn = false;
$role = "";
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true){
  $username = $_SESSION["username"];
  $role = $_SESSION["role"];
  $loggedIn = $_SESSION["loggedIn"];
  if($role == "admin" ){
    //header("Location: dashboard.php");
  }
  
} else {
    header("Location: login.php");
}

$teachers = getTeachers();
$students = getStudents();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Signin Template · Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/">

    <!-- Bootstrap core CSS -->
<!-- <link href="../assets/dist/css/bootstrap.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .navbar{
      font-size: 12px;
    }
    </style>
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
<!--         <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
    <img src="./logo.svg" width="100" height="70" alt="" loading="lazy">
  </a>
   <a class="navbar-brand" href="#">Code Gurukul</a> 
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
     
      </span>
    
    
    <button class="btn btn-primary my-2 my-sm-0" type="submit">Book Free Trial (Sign Up)</button>
     <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Book Free Trial</button>
    </form> 
  </div>
</nav>-->
      <br>
        <div class="container-fluid">
  <div class="row">
    <form class="form-signin" method=POST action="https://beta.code-gurukul.com/adminlogin.php">

  <h1 class="h3 mb-3 font-weight-normal">Login As</h1>
  <?php
if(isset($_POST["submit"])){
  $user = checkUser($_POST["username"],$_POST["password"] );
//   echo '<pre>';
// print_r($user);
if(!empty($user)){
  session_start();
      $_SESSION["loggedIn"] = true;
      $_SESSION["role"] = $user->role;
      $_SESSION["username"] = $user->first_name .' '. $user->last_name;
      $_SESSION["email"] = $user->email;
      $_SESSION["user_id"] = $user->user_id;
      print_r($_SESSION);
     
      header("Location: dashboard.php");
  } else {
      echo "<p style='color:red'> Invalid Username or Password </p>";
  }
} ?>
  
  <div class="form-group ">
                            <label class="col-sm-2 col-form-label">Teachers</label>
                            <div class="col-sm-10">
                                <?php
                                echo '<select class="form-control"  name=teacher_id id=teacher>';
                                echo "<option value= > select teacher </option>";
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
                                 echo "<option value= > select student </option>";
                                foreach ($students as $student) {
                                    echo "<option value=$student->user_id >".ucfirst($student->first_name) ." "  .ucfirst( $student->last_name) ." ( "  .ucfirst($teacher->category).") </option>";
                                }
                                echo '</select>';
                                ?></div>
                        </div>
   <input type="hidden" id="admin" class="form-control" placeholder="Username" required autofocus name="username">
  <label for="username" class="sr-only">Username</label>
  <input type="text" id="username" class="form-control" placeholder="Username" required autofocus name="username">
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
  
  <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Log in</button>

</form>


  </div>
        </div>
  </body>
</html>
