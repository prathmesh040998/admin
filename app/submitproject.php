<?php
 ini_set("error_reporting", E_ALL);
 ini_set("display_errors", true);
include_once("db.php");
$projectId = $_POST['schedule_id'];

$action = ($_POST['action'])? $_POST['action']: null;
if($action == 'saveLink'){
    $link = $_POST['project_submit_link'];
    $response = submitProjectLink($projectId, $link);
}

if($action == 'sessionMarkConducted'){
    $response = sessionMarkConducted($projectId);
}

if($action == 'saveComments'){
    $comments = $_POST["comments"];
    $response = saveComments($projectId, $comments);
}


if($response){
    echo 'saved';
}else {
    echo 'failed';
}


