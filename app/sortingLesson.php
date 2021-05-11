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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />

  <title>Sorting Lesson</title>
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

      <span class="nav-item float-right">
        <a class="nav-link" target="_blank" href="main.php" tabindex="-1">My Dashboard</a>
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
      <!-- <form class="form-inline my-2 my-lg-0">
                  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Book Free Trial / Sign Up</button>
                </form> -->
    </div>
  </nav>
  <div class="container text-center">
    <div class="col-sm-12">
      <label class="col-sm-2 col-form-label">Courses</label>
      <select class="form-control" name="course_id" id="courses">
        <option value="">Select Course</option>
        <option value="1">Beginner</option>
        <option value="2">Intermediate</option>
        <option value="3">Advance</option>
      </select>
    </div>
    <table class="table text-center">
      <thead>
        <tr>
          <th scope="col">New seq</th>
          <th scope="col">Lesson Id</th>

          <th scope="col">Old seq</th>
          <th scope="col">Name</th>
          <th scope="col">Up</th>
          <th scope="col">Down</th>
        </tr>
      </thead>
      <tbody id="data"></tbody>
    </table>
    <button class="btn save-btn btn-success mb-5" onclick="save_new_seq_data()">Save</button>
    <div id="showError" class="mt-5">
      <h2><b>Please Select Course</b></h2>
    </div>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // ajax call
    //get lesson according to course
    var temp = Array();
    $(".table").hide();
    $(".save-btn").hide();

    $("#courses").change(function() {
      getCourseData();
    });

    function getCourseData() {
      let courseId = $("#courses").val();
      console.log(courseId);
      $.ajaxSetup({
        async: false
      });
      if (courseId != "") {
        $(".table").show();
        $(".save-btn").show();
        $("#showError").hide();
        $.post(
          "db.php", {
            course_id: courseId,
            getLessonData: "getLessonData",
          },
          function(data, status) {
            // console.log(data);
            temp = data;
          }
        );
      } else {
        $(".table").hide();
        $(".btn").hide();
        $("#showError").show();
      }
      display();
    }
    //move function
    function move(arr, old_index, new_index) {
      while (old_index < 0) {
        old_index += arr.length;
      }
      while (new_index < 0) {
        new_index += arr.length;
      }
      if (new_index >= arr.length) {
        var k = new_index - arr.length;
        while (k-- + 1) {
          arr.push(undefined);
        }
      }
      arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
      return arr;
    }
    // move function call
    function action(k, m) {
      temp = move(temp, k, m);
      display();
    }

    // call display table
    display();
    // dispaly function
    function display() {
      console.log("display function calll");
      $("#data").empty();
      for (var key in temp) {
        var next = key;
        next++;
        var pre = key - 1;
        if (pre == -1) {
          var buttonDisablePre = "disabled";
        } else {
          var buttonDisablePre = "";
        }
        if (next == temp.length) {
          var buttonDisableNext = "disabled";
        } else {
          var buttonDisableNext = "";
        }
        $("#data").append(`
              <tr>
                <th scope='row'>${eval(key+"+"+1)}</th>
                <td>${temp[key].lesson_id}</td>
                <td>${temp[key].sequence}</td>
                <td>${temp[key].lesson_name}</td>
                
                <td>
                  <button type="button" ${buttonDisablePre} class="btn btn-outline-warning" onclick="action(${key},${pre})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-up-short" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
                    </svg>
                  </button>
                </td>
                <td>
                  <button type="button" ${buttonDisableNext} class="btn btn-outline-danger" onclick="action(${key},${next})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-down-short" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                    </svg>
                  </button>
                </td>
              </tr>`);
      }
    }

    //empty array
    order_data = Array();
    //save data function
    function save_new_seq_data() {
      while (order_data.length > 0) {
        order_data.pop();
      }
      for (var key in temp) {
        order_data.push(temp[key].lesson_id);
      }
      let new_seq = order_data.join(",");
      $.ajaxSetup({
        async: false
      });
      $.post(
        "db.php", {
          newSeq: new_seq,
          setNewSeq: "setNewSeq",
        },
        function(data, status) {
          // console.log(data);
          alert(data);

        }
      );
      alert(new_seq);
      getCourseData();
    }



    function arrage() {
      $.post(
        "db.php", {
          getMax: "getMax"
        },
        function(data, status) {
          // console.log(data);
          alert(data);

        }
      )
    }
  </script>
</body>

</html>