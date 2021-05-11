<?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
$questions = [1 =>
  "What is an object?",
  "What is Synchronous and Asynchronous programming?", 
 "What is a Promise?", 
 "What is async - await?", 
 "What is Ajax and its states and example?", 
 "Web Services - HTTP requests and HTTP status codes?", 
 "Data types in JavaScript/any language?", 
 "What is Dependency Injection?", 
 "List the Access Modifiers of Class.", 
 "Database: What are the ACID properties?", 
 "Database: Different types of JOINS?", 
 "Database: Design db to store tree structure?", 
 "Code: (any language) Write a Binary Search program.", 
 "Code: (any language) Write a program demonstrating use of Linked List.", 
 "Code: (any language) Demonstrate a Registration/Login HTML form and Rest API to authenticate users", 
 "What is React?", 
 "What is Node?", 
 "Explain any framework of your choice highlighting the conceptual understanding, the utiities, advantages & disadvantages and choice areas as per your knowledge.", 
 "Share any Technical Challenge faced by you during any of the projects / tasks undertaken till now.", 
 "Fill the box with anything."
];

session_start();
//print_r($_SESSION);

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true){
  $username = $_SESSION["username"];
  $email = $_SESSION["email"];
  $mobile = $_SESSION["mobile"];
  $loggedIn = $_SESSION["loggedIn"];
 
}else {
  header("Location: /");
} 

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Code Gurukul</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/">

    <!-- Bootstrap core CSS -->
<!-- <link href="../assets/dist/css/bootstrap.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


   
    <!-- Custom styles for this template -->
    <!-- <link href="signin.css" rel="stylesheet"> -->
  </head>
  <body>
      <div class="container">
      <img class="mb-4" src="logo.svg" alt="" width="172" height="172">
    <form method=POST >
    <ul>
    <li>
      Do not refresh the page.
    </li>
    
  </ul>
  <h1 class="h3 mb-3 font-weight-normal">Questions</h1>
  
  
 
  <?php foreach($questions as $id => $question){
    // echo '<p> Question '. $id .' : '. $question . '</p>';
    // echo '<textarea class="btn-border-primary"  width="700px" name=" '. $id .'" >' . '</textarea>';

    echo '<div class="form-group">
    <label for="exampleFormControlTextarea1">Question '. $id .' : '. $question .'</label>
    <textarea class="form-control" id=" '. $id .'" rows="3" name=" '. $id .'"></textarea>
  </div> ';
  }
  ?>
  <!-- 
   
  <div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Accept Terms and Conditions
    </label>
  </div> -->
  <button class="btn btn-primary" type="submit" name="submit" onclick="return confirm('Are you sure?');">Submit</button>
  <input type="hidden" name="username" value= <?= $username ?> />
  <input type="hidden" name="email" value= <?= $email ?> />
  <input type="hidden" name="mobile" value= <?= $mobile ?> />
  <p class="mt-5 mb-3 text-muted">&copy; Code Gurukul </p>

</form>
      </div>
</body>
</html>

<?php
if(isset($_POST["submit"])){
  //print_r($_REQUEST);
  // $_POST['user'] = $username;
  // $_POST['email'] = $email;
  // $_POST['mobile'] = $mobile;
 
    $fileName = $username .'_'. $email.'_'. time();
    file_put_contents($fileName, print_r($_POST, true));
    //ob_start();
    session_destroy();
    //header("Location: /");
    //ob_end_flush();
    echo '<script> alert ("Thanks for submitting your test!") 
    window.location = "https://test.code-gurukul.com";
    </script>';
  }
  ?>
  
