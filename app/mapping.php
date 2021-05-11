<?php
session_start();
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
  <style>
    /* .bd-placeholder-img {
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

    .navbar {
      font-size: 12px;
    } */
  </style>
  <!-- Custom styles for this template -->
  <!-- <link href="signin.css" rel="stylesheet"> -->
</head>

<body class="text-center">
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
  <br>
  <div class="container">
    <form class="form-signin col-md-5" id="mapping" method=POST ">
      <h1 class="h3 mb-3 font-weight-normal">Student Teacher Mapping</h1>
      <div class="form-group">
        <label class="col-sm-2 col-form-label">Teachers</label>
        <div class="col-sm">
          <?php
          echo '<select class="form-control"  name=teacher_id id=teacher>';
          echo "<option value= > select teacher </option>";
          foreach ($teachers as $teacher) {
            echo "<option value=$teacher->user_id > " . ucfirst($teacher->first_name) . " "  . ucfirst($teacher->last_name) . " ( "  . ucfirst($teacher->user_id) . ") </option>";
          }
          echo '</select>';
          ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 col-form-label">Students</label>
        <div class="col-sm">
       
          <select class="form-control" name=student_id id=student >;
          <option value= > select student </option>;
          <?php
            foreach ( $students as $student) {
            echo "<option value=$student->user_id />" . ucfirst($student->first_name) . " "  . ucfirst($student->last_name) . " ( "  . ucfirst($student->category) . ") ( "  . ucfirst($student->user_id) . " - "  . ucfirst($student->teacher_id) . ") </option>";
          }
         
          ?>
          </select>
          
          
          </div>


        <button class="btn btn-lg btn-primary btn-block mt-5" type="submit" name="submit">Save</button>
    </form>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  </div>
  </div>
  <script>
    $(document).ready(function() {
      $("#mapping").submit(function(event) {
        event.preventDefault()
        let data = $("#mapping").serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});
        data.action = "mapStudentTeacher";
        if (data.teacher_id != "" && data.student_id != "") {
          console.log(data);
          $.post("a.php", data)
            .done(function(resultData) {
              alert("" + resultData);
              location.reload();
            });
        } else {
          alert("Select valid option")
        }


      });
    });
  </script>
</body>

</html>