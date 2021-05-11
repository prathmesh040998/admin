<?php
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

//   echo "event: message\n";
//   echo 'data: {"msg": "confetti"}';
//   echo "\n\n";
//   ob_end_flush();
//   flush();
 
// function sendMsg($id, $msg) {
//     echo "id: $id" . PHP_EOL;
//     echo "data: $msg" . PHP_EOL;
//     echo PHP_EOL;
//     ob_flush();
//     flush();
//   }
 
//   $serverTime = time();

//   sendMsg($serverTime, 'server time: ' . date("h:i:s", time())); 