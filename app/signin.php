<?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
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
    </style>
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <form class="form-signin" method=POST >
  <img class="mb-4" src="../assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Please log in</h1>
  <label for="username" class="sr-only">Username</label>
  <input type="text" id="username" class="form-control" placeholder="usename" required autofocus name="username">
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
  <!-- <div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Accept Terms and Conditions
    </label>
  </div> -->
  <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
  <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p>
</form>
</body>
</html>

<?php
if(isset($_POST)){
  print_r($_REQUEST);
 
  if(isset($_POST["username"])){
    if($_POST["username"] === "anil"){
      session_start();
      $_SESSION["loggedIn"] = true;
      $_SESSION["role"] = "teacher";
      $_SESSION["username"] = $_POST["username"];
      print_r($_SESSION);
      header("Location: index.php");

    }
    if($_POST["username"] === "shinde"){
      session_start();
      $_SESSION["loggedIn"] = true;
      $_SESSION["role"] = "student";
      $_SESSION["username"] = $_POST["username"];
      print_r($_SESSION);

    }
  }
}