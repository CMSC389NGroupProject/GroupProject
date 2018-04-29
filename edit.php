<?php
require_once "support.php";

session_start();

$message = "";
$content = "";
$warning = "";
$_SESSION['update_result'] = false;

if ($_SESSION['email'] != null) {
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
} else {
    header ("Location: index.html");
}

if (isset($_POST['Update'])) {
    
    $emailValue = trim($_POST['email']);
    $nameValue = trim($_POST['name']);
    $telValue = trim($_POST['phone_validation']);
    $gender = $_POST['gender'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    /* Connecting to the database */
    $db_connection = connectToDB();
    
    
    
    $stmt = $db_connection->prepare("UPDATE users SET name=?, email=?, tel=?, gender=?, password=? WHERE email=?");
    $stmt->bind_param("ssssss",$nameValue,$emailValue,$telValue, $gender, $newPassword, $_SESSION['email']);
    if ($stmt->execute()) {
        $content .= "<p><b>Name: </b>$nameValue</p>";
        $content .= "<p><b>Email: </b>$emailValue</p>";
        $content .= "<p><b>tel: </b>$telValue</p>";
        $content .= "<p><b>Gender: </b>$gender</p>";
        $_SESSION['update_result'] = true;
    } else {
        $warning = "<p style='color: red'> Fail to update this user. Please try again with correct information</p>";
    }
    
    $stmt->close();
    $db_connection->close();
    
    $message = "The entry has been updated in the database and the new values are:";
    
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
<ul class="nav nav-tabs">
    <li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
    <li><a href="contact.html">Contact Us</a></li>

</ul>
<div style="padding-left: 38em; padding-right: 38em">
<form action="{$_SERVER["PHP_SELF"]}" method="post" id="form">
<div class="form-group">
<h2>Edit Profile</h2>
<label style="display: block;">
<b>Name: </b> 
<input type="text" name="name" value="$name" required><br>
</label>

<label style="display: block;">
<b>Email: </b> 
<input type="email" name="email" value="$email" required><br>
</label>

<label style="display: block;">
<b>Phone number: </b> 
<input type="text" name="phone_validation" pattern="\([0-9]{3}\)[0-9]{3}[\-][0-9]{4}$" required
title="Please enter in form: (123)456-7890" value="$tel">

</label>

<div class="custom-select" style="width: 200px">
<strong>Gender: </strong>
<select name="gender" size="1" required >
<option value="N">Prefer Not Answer</option>
<option value="M">Male</option> 
<option value="F">Female</option>
</select><br><br>
</div><br>

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
<form method="post">
<button type="submit" name="back">Back to Profile</button>
</form>
</div>

<script>
        var x, i, j, selElmnt, a, b, c;
        /*look for any elements with the class "custom-select":*/
        x = document.getElementsByClassName("custom-select");
        for (i = 0; i < x.length; i++) {
          selElmnt = x[i].getElementsByTagName("select")[0];
          /*for each element, create a new DIV that will act as the selected item:*/
          a = document.createElement("DIV");
          a.setAttribute("class", "select-selected");
          a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
          x[i].appendChild(a);
          /*for each element, create a new DIV that will contain the option list:*/
          b = document.createElement("DIV");
          b.setAttribute("class", "select-items select-hide");
          for (j = 0; j < selElmnt.length; j++) {
            /*for each option in the original select element,
            create a new DIV that will act as an option item:*/
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function(e) {
                /*when an item is clicked, update the original select box,
                and the selected item:*/
                var y, i, k, s, h;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                h = this.parentNode.previousSibling;
                for (i = 0; i < s.length; i++) {
                  if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    for (k = 0; k < y.length; k++) {
                      y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                  }
                }
                h.click();
            });
            b.appendChild(c);
          }
          x[i].appendChild(b);
          a.addEventListener("click", function(e) {
              /*when the select box is clicked, close any other select boxes,
              and open/close the current select box:*/
              e.stopPropagation();
              closeAllSelect(this);
              this.nextSibling.classList.toggle("select-hide");
              this.classList.toggle("select-arrow-active");
            });
        }
        function closeAllSelect(elmnt) {
          /*a function that will close all select boxes in the document,
          except the current select box:*/
          var x, y, i, arrNo = [];
          x = document.getElementsByClassName("select-items");
          y = document.getElementsByClassName("select-selected");
          for (i = 0; i < y.length; i++) {
            if (elmnt == y[i]) {
              arrNo.push(i)
            } else {
              y[i].classList.remove("select-arrow-active");
            }
          }
          for (i = 0; i < x.length; i++) {
            if (arrNo.indexOf(i)) {
              x[i].classList.add("select-hide");
            }
          }
        }
        document.addEventListener("click", closeAllSelect);
        
   </script>
EOBODY;

$updated = <<<EOBODY
<ul class="nav nav-tabs">
	<li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
    <li><a href="contact.html">Contact Us</a></li>
</ul>
<h2>$message</h2>
<form action="{$_SERVER["PHP_SELF"]}" method="post">
$content
</form>
$warning
EOBODY;

if (isset($_POST['back'])) {
    header("location: userinterface.php");
}

if ($_SESSION['update_result']) {
    $body = $updated;
    session_unset();
    session_destroy();
}

$page = generatePage($body, "edit");
echo $page;

?>