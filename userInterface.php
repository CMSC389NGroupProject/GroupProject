<!doctype html>
<html>
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>User Interface</title>
            <link href="calendar.png" rel="icon" type="image/x-icon" />
            <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
            <link rel = "stylesheet" href = "style.css">

    </head>
    
    <body>

        <nav class="navbar navbar-default">
        <ul class="nav nav-tabs">
            <li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="logout.php">Log Out</a></li>
            <li><a href="contact.html">Contact Us</a></li>

        </ul>
    </nav>


<?php
require("support.php");

session_start();

if (isset($_COOKIE['login'])) {
<<<<<<< HEAD

        $db_connection = connectToDB();

        $query = "SELECT * FROM users WHERE email ='{$_SESSION['email']}'";

        $result1 = mysqli_query($db_connection, $query);


        while($row = mysqli_fetch_array($result1)){

            $name = $row['name'];

            $email = $row['email'];

            $tel = $row['tel'];

            $gender = $row['gender'];

            $passwordValue = $row['password'];

            $imagedata = $row['image'];

        }

    /* Freeing memory */
    mysqli_free_result($result);
    
    
    /* Closing connection */
    mysqli_close($db_connection);



=======
    $db_connection = connectToDB();

    $query = "SELECT * FROM users WHERE email='{$_SESSION['email']}'";
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
                } else {
                    $checkedFemale = "checked";
                }
			}
		}
	}
	
	/* Freeing memory */
	$result->close();
	
	/* Closing connection */
    $db_connection->close();
>>>>>>> 7a8c36d7189c203d336d7870e55a33725a0d9ed5
}else {
    header ("Location: login.php");
}
?>


<div style="padding: 30em;padding-top: 10px">

    <div class="card" style="padding: 15px">
        
<<<<<<< HEAD
        <?php 
        echo '<img width="100%" height="100%" src="data:image/jpg;base64,'.base64_encode($imagedata).' "> ';
        ?>

=======
        <img src="img_avatar.png" alt="Avatar" style="width:100%">
>>>>>>> 7a8c36d7189c203d336d7870e55a33725a0d9ed5
        <br>
            <h3 style="color:blue;text-align:center;"><?php echo $name ?></h3>
            <p class="title">Email: <?php echo $email ?></p>
            <p class="title">Tel: <?php echo $tel ?></p>
            <p class="title">Gender: <?php
                if ($gender === "N") {
                    echo "Prefer Not Answer";
                } else {
                    echo $gender;
                 }?></p>

    </div>

    <div>
        <br>

        <button type="submit" onclick="location.href ='edit.php';">update your profile</button>
        <button type="submit" onclick="location.href ='group.php';">Calendar</button>
    </div>


</div>


    </body>
    
</html>

















