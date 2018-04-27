<?php
require_once "support.php";

$upper = <<<EOBODY
    <script>
        var check = function() {
            if (document.getElementById('pwd').value == document.getElementById('verifyPwd').value) {
                document.getElementById('message').style.color = 'green';
                document.getElementById('message').innerHTML = 'Matching';
            } else {
                document.getElementById('message').style.color = 'red';
                document.getElementById('message').innerHTML = 'Not Matching';
            }
        }
    </script>
<div>
<form method="post" action="{$_SERVER['PHP_SELF']}">
<ul class="nav nav-tabs">
    <li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
    <li><a href="contact.php">Contact Us</a></li>

</ul>

<div id = 'cent'>

    <h1>Register New Account</h1>
    <strong>Name: </strong>
    <input type="text" name="name" required><br><br>
    <strong>Email: </strong>
    <input type="email" name="email" required><br><br>
    <strong>Phone Number: </strong>
    <input type="text" name="phone_validation" pattern="\([0-9]{3}\)[0-9]{3}[\-][0-9]{4}$" required 
    title="Please enter in form: (123)456-7890" placeholder="(123)456-7890"><br><br>
    <strong>Gender: </strong>
    <select name="gender" size="1" required>
        <option value="N">Prefer Not Answer</option>
        <option value="M">Male</option>
        <option value="F">Female</option>
    </select><br><br>
    <strong>Password: </strong>
    <input type="password" name="pwd" id="pwd" onkeyup="check()" required><br><br>
    <strong>Verify Password: </strong>
    <input type="password" name="verifyPwd" id="verifyPwd" onkeyup="check()" required>
    <span id="message"></span><br><br>
    <input type="submit" name="submit"  value="Register"><br><br>
    <button onclick="location.href='index.html'">Return to main menu</button><br />
    </div>
</form>
<div/>
EOBODY;


$bot="";
if (isset($_POST['submit'])) {
    if ($_POST['pwd'] !== $_POST['verifyPwd']) {
        $bot = "<strong>Password does not match</strong>";
    } else {
        $table = "users";
        $hashed = password_hash(trim($_POST["pwd"]), PASSWORD_DEFAULT);
        $db = connectToDB();

        $sqlQuery = sprintf("insert into $table (name,email,tel,gender,password) values ('%s','%s','%s','%s','%s')",
            trim($_POST['name']), trim($_POST['email']), trim($_POST['phone_validation']), trim($_POST['gender']), $hashed);
        $result = mysqli_query($db, $sqlQuery);
        if ($result) {
            $upper = "<h1>Thank you for register, please go back to main page and login</h1>";
            $upper .= "<a href='index.html'><button>Return to main menu</button></a>";
        } else {
            $bot = "<br>Register failed: " . mysqli_error($db) . "<br><br>";
        }
    }
}

echo generatePage($upper.$bot, "Register");