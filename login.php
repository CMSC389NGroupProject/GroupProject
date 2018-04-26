<?php
require("support.php");

$topPart = <<<EOBODY
<form action="{$_SERVER['PHP_SELF']}" method="post" class = "form-horizontal">
<ul class="nav nav-tabs">
	<li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>
 </ul>

<div id = 'cent'>
<h1>Log into Our System</h1>
<strong>Email: </strong>
<input type="text" name="email"  required/></br></br>
<strong>Password: </strong>
<input type="password" name="password" required/></br></br>
<input type="submit" name="submitInfoButton" value = "Login"/></br>
<<<<<<< HEAD
=======
</div>

>>>>>>> a87a180468fc38838ac20a42050c9676fe5798e9
</form>
EOBODY;

$bottomPart = "";

if (isset($_POST["submitInfoButton"])) {
		$login = trim($_POST["email"]);
		$password = trim($_POST["password"]);
		$db = connectToDB();
        $sqlQuery = sprintf("select password from users where email='%s'", $login);
        $result = mysqli_query($db,$sqlQuery);

if ($result) {
    if (mysqli_num_rows($result) == 0) {
        $bottomPart .= "<h2>Please Register</h2>";
    	$bottomPart .="<a href=\"register.php\"><button>Register</button></a>";
        $bottomPart .="<a href=\"index.html\"><button>Return to main menu</button></a>";
        $bottomPart .= "</div>";

    }
    else{
        $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (!password_verify($password, $recordArray['password'])) {
			$bottomPart .= "<strong><h1>Invalid login information provided.</strong><h1><br />";
            $bottomPart .= "</div>";
		}
		else {
			session_start();
			$_SESSION['user'] = $login;
            $_SESSION['email'] = $login;

<<<<<<< HEAD
			header("location:userinterface.php");
=======
			header("location:edit.php");
>>>>>>> a87a180468fc38838ac20a42050c9676fe5798e9
		}
	}
}else{
    $bottomPart .= "<h2>Cannot Connect to Database Please Contact Us in Contact</h2>";
    $bottomPart .="<a href=\"register.php\"><button>Register</button></a>";
    $bottomPart .="<a href=\"index.html\"><button>Return to main menu</button></a>";
    $bottomPart .= "</div>";
}


}
$body = $topPart.$bottomPart;
$page = generatePage($body, "Login");
echo $page;
?>