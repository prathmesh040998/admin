<?php

$servername=getenv('MYSQL_Host');
$password=getenv('MYSQL_Pass');
$username = "root";
$database = "cg_dev";

// Create connection
$conn = new mysqli($servername, $username, $password,$database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

/****get lesson data using course id*****/ 
if(isset($_POST['courseId']))
{
	$result_array = array();

	$course_id = (int)$_POST['courseId'];
	// Cretae Sql query
	$sql = "select * from lessons where course_id = ".$course_id;
	$result = mysqli_query($conn, $sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        array_push($result_array, $row);
	    }
	}
	header('Content-type: application/json');
	echo json_encode($result_array);
}
/****end get lesson data using course id*****/ 
/****get Link data using lesson id*****/ 
if(isset($_POST['lessonId']))
{
	$result_array = array();

	$lesson_id = $_POST['lessonId'];
	// Cretae Sql query
	$sql = "select * from links where lesson_id = $lesson_id";
	$result = mysqli_query($conn, $sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        array_push($result_array, $row);
	    }
	}
	header('Content-type: application/json');
	echo json_encode($result_array);
}
/****end get Link data using lesson id*****/ 


?>