<?php
require("support.php");

session_start();

if ($_SESSION['user'] != null) {
    $db_connection = connectToDB();

    $query = "SELECT * FROM users WHERE email='{$_SESSION['user']}'";
    $result = $db_connection->query($query);
    if (!$result) {
		die("Retrieval failed: ". $db_connection->error);
	} else {
		/* Number of rows found */
		$num_rows = $result->num_rows;
		if ($num_rows === 0) {
			echo "Empty Table<br>";
		} else {
			for ($row_index = 0; $row_index < $num_rows; $row_index++) {
				$result->data_seek($row_index);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                $name = $row['name'];
                $email = $row['email'];
                $tel = $row['tel'];
                $gender = $row['gender'];
                $passwordValue = $row['password'];
                
                if ($gender === 'M') {
                    $checkedMale = "checked";
                    $checkedFemale = "";
                } else {
                    $checkedMale = "";
                    $checkedFemale = "checked";
                }
			}
		}
	}
	
	/* Freeing memory */
	$result->close();
	
	/* Closing connection */
    $db_connection->close();
}else {
    header ("Location: index.html");
}
?>



<!doctype html>
<html>
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>$title</title>	
            <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
            <link rel = "stylesheet" href = "style.css">
    </head>
    
    <body>
    	
    	Name: <?php $name ?>
    	<br>



    </body>


    
</html>

















