<?php
require_once "support.php";

session_start();

if ($_SESSION['user']) {
    $db_connection = connectToDB();
    $stmt = $db_connection->prepare("SELECT * FROM applicants WHERE email=?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $stmt->store_result();
    $num_rows = $stmt->num_rows;
    $stmt->bind_result($name, $email, $tel, $gender, $passwordValue);
    while ($stmt->fetch()) {
        if ($gender === 'M') {
            $checkedMale = "checked";
            $checkedFemale = "";
        } else {
            $checkedMale = "";
            $checkedFemale = "checked";
        }
    }
    $stmt->free_result();
    $stmt->close();
    $db_connection->close();
} 

if (isset($_POST['Update'])) {
    $emailValue = trim($_POST['email']);
    $nameValue = trim($_POST['name']);
    $telValue = trim($_POST['phone_validation']);
    $gender = $_POST['gender'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    /* Connecting to the database */
    $db_connection = conncetTODB();
    $stmt = $db_connection->prepare("UPDATE users SET name=?, email=?, tel=?, gender=?, password=? WHERE email=?");
    $stmt->bind_param("ssssss",$nameValue,$emailValue,$telValue, $newPassword, $gender, $_SESSION['email']);
    if ($stmt->execute()) {
        $content .= "<p><b>Name: </b>$nameValue</p>";
        $content .= "<p><b>Email: </b>$emailValue</p>";
        $content .= "<p><b>tel: </b>$telValue</p>";
        $content .= "<p><b>Gender: </b>$gender</p>";
    } else {
        $warning = "<p style='color: red'> Fail to update this user. Please try again with correct information</p>";
    }

    $stmt->close();
    $db_connection->close();

    session_unset();
    session_destroy();

    $message = "The entry has been updated in the database and the new values are:";
} else {
    header ("Location: index.html");
}

$body = <<<EOBODY
    <script>
        var check = function() {
            if (document.getElementById('password').value == document.getElementById('verifyPassword').value) {
                document.getElementById('message').style.color = 'green';
                document.getElementById('message').innerHTML = 'matching';
                document.getElementById('form').setAttribute("action", "{$_SERVER["PHP_SELF"]}");
            } else {
                document.getElementById('message').style.color = 'red';
                document.getElementById('message').innerHTML = 'not matching';
                document.getElementById('form').removeAttribute("action");
            }
        }
    </script>

    <form action="{$_SERVER["PHP_SELF"]}" method="post" id="form">
        <div class="form-group">
            <label style="display: block;">
                <b>Name: </b> 
                <input type="text" name="name" value="$name" required><br>
            </label>

            <label style="display: block;">
                <b>Email: </b> 
                <input type="email" name="email" value="$email" required><br>
            </label>

            <label style="display: block;">
                <b>Email: </b> 
                <input type="text" name="phone_validation" pattern="\([0-9]{3}\)[0-9]{3}[\-][0-9]{4}$" required
               title="Please enter in form: (123)456-7890" class="form-control" value="$tel">
            </label>

            <label style="display: block;">
                <b>Gender: </b>
                <input type="radio" name="gender" value="M" $checkedMale> M
                <input type="radio" name="gender" value="F" $checkedFemale> F
            </label>

            <label style="display: block;">
                <b>New Password: </b>
                <input type="password" name="password" id="password" onkeyup='check();' required>
            </label>

            <label style="display: block;">
                <b>Verify Password: </b>
                <input type="password" name="verifyPassword" id="verifyPassword" onkeyup='check();' required>
            </label>
            <span id='message'></span>
        </div>

        <button type="submit" name="Update">Submit Change</button>
    </form>
EOBODY;

$updated = <<<EOBODY
    <h2>$message</h2>
    <form action="{$_SERVER["PHP_SELF"]}" method="post">
        $content
    </form>
$warning
EOBODY;

$page = generatePage($body);
echo $page;

?>