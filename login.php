<?php
require("support.php");

$topPart = <<<EOBODY
<form action="{$_SERVER['PHP_SELF']}" method="post" class = "form-horizontal">
<ul class="nav nav-tabs">
	<li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
    <li><a href="contact.html">Contact Us</a></li>
</ul>

<div id = 'cent'>
<h1>Log into Our System</h1>
<strong>Email: </strong>
<input type="text" name="email" /></br></br>
<strong>Password: </strong>
<input type="password" name="password" /></br></br>
<input type="submit" name="submitInfoButton" value = "Login" style="color: white" /></br>
<button type="submit" name="register">Register</button>

</form>
EOBODY;

$bottomPart = "";

$body = <<<EBODY
    <br>
    <strong>No such user please register</strong>

</div>
EBODY;

if (isset($_COOKIE['login'])){
    header("location:userInterface.php");
}
else {
    if(isset($_POST["register"])){
        header("location:register.php");
    }
    if (isset($_POST["submitInfoButton"])) {
        $login = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $db = connectToDB();
        $sqlQuery = sprintf("select password from users where email='%s'", $login);

        $result = mysqli_query($db, $sqlQuery);

        if ($result) {
            if (mysqli_num_rows($result) == 0) {
                $bottomPart = $body;
            } else {
                $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if (!password_verify($password, $recordArray['password'])) {
                    $bottomPart .= "<strong>Invalid login information provided.</strong><br />";
                    $bottomPart .= "</div>";
                } else {
                    if (!isset($_COOKIE['login'])){
                        $name = "login";
                        setcookie($name,$login);
                    }
                    session_start();
                    
                    $_SESSION['email'] = $login;
                    header("location:userInterface.php");
                }
            }
        } else {
            $bottomPart .= "<h2>Cannot Connect to Database Please Contact Us in Contact</h2>";
            $bottomPart .= "</div>";
        }
    }
}

$body = $topPart.$bottomPart;
$page = generatePage($body, "Login");
echo $page;
?>