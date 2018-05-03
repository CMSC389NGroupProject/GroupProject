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
    mysqli_free_result($result1);
    
    
    /* Closing connection */
    mysqli_close($db_connection);



}else {
    header ("Location: login.php");
}
?>


<div style="padding: 30em;padding-top: 10px">

    <div class="card" style="padding: 15px">
        
        <?php 
        if (empty($imagedata)){
            // $imagedata = "img_avatar.jpg";
            ?>
            <img src="img_avatar.jpg" width="100%" height="100%">
            <?php
        }else{
            echo '<img width="100%" height="100%" src="data:image/jpg;base64,'.base64_encode($imagedata).' "> ';
        }
        ?> 

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

















