<?php
// Tell PHP that we're using UTF-8 strings until the end of the script
mb_internal_encoding('UTF-8');
$utf_set = ini_set('default_charset', 'utf-8');
if (!$utf_set) {
    throw new Exception('could not set default_charset to utf-8, please ensure it\'s set on your system!');
}

// Tell PHP that we'll be outputting UTF-8 to the browser
mb_http_output('UTF-8');
 
// Our UTF-8 test string
$string = 'Êl síla erin lû e-govaned vîn.';

// Transform the string in some way with a multibyte function
// Note how we cut the string at a non-Ascii character for demonstration purposes
$string = mb_substr($string, 0, 15);

// Connect to a database to store the transformed string
// See the PDO example in this document for more information
// Note the `charset=utf8mb4` in the Data Source Name (DSN)

$mshost=getenv('MYSQL_Host');
$mspasswd=getenv('MYSQL_Pass');
$link = new PDO(
    sprintf('mysql:host=%s;dbname=cg_2b_dev_v2', $mshost),
    'root',
    $mspasswd,
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false
    )
);

// $link = new PDO(
//    'mysql:host=localhost;dbname=cg_dev;charset=utf8mb4',
//    'root',
//    'Anil@123',
//    array(
//        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_PERSISTENT => false
//    )
// );

// Store our transformed string as UTF-8 in our database
// Your DB and tables are in the utf8mb4 character set and collation, right?
// $handle = $link->prepare('insert into ElvishSentences (Id, Body, Priority) values (default, :body, :priority)');
// $handle->bindParam(':body', $string, PDO::PARAM_STR);
// $priority = 45;
// $handle->bindParam(':priority', $priority, PDO::PARAM_INT); // explicitly tell pdo to expect an int
// $handle->execute();

// Retrieve the string we just stored to prove it was stored correctly
$role = "teacher";
$handle = $link->prepare('select * from courses as c 
left join lessons as l on c.course_id = l.course_id 
left join links as li on l.lesson_id=li.lesson_id where li.for="'.$role.'"');
// $id = 7;
// $handle->bindParam(':id', $id, PDO::PARAM_INT);
$handle->execute();

// Store the result into an object that we'll output later in our HTML
// This object won't kill your memory because it fetches the data Just-In-Time to
$result = $handle->fetchAll(\PDO::FETCH_OBJ);
echo '<pre>';
print_r($result);
//echo '<a href="/code/teacher.php?join='. $result[0]->room_id .'">Join </a>';
exit;
// An example wrapper to allow you to escape data to html
function escape_to_html($dirty){
    echo htmlspecialchars($dirty, ENT_QUOTES, 'UTF-8');
}

header('Content-Type: text/html; charset=UTF-8'); // Unnecessary if your default_charset is set to utf-8 already
?><!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>UTF-8 test page</title>
    </head>
    <body>
        <?php
        foreach($result as $row){
            escape_to_html($row->Body);  // This should correctly output our transformed UTF-8 string to the browser
        }
        ?>
    </body>
</html>