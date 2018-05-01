<?php

function connectToDB() {
    $host = "localhost";
    $user = "dbuser";
    $password = "goodbyeWorld";
    $database = "applicationdb";

    $db = mysqli_connect($host, $user, $password, $database);
    if (mysqli_connect_errno()) {
        echo "Connect failed.\n".mysqli_connect_error();
        exit();
    }
    return $db;
}
function generatePage($body, $title="Grades Submission System") {
    $page = <<<EOPAGE
<!doctype html>
<html>
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>$title</title>	
            <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
            <link href="calendar.png" rel="icon" type="image/x-icon" />
            <link rel = "stylesheet" href = "style.css">
    </head>
    
    $body
    
</html>
EOPAGE;
    return $page;
}


?>