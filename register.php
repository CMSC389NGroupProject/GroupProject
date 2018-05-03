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
        document.getElementById('image').onchange = uploadOnChange;
        function uploadOnChange() {
            var filename = this.value;
            var lastIndex = filename.lastIndexOf("\\");
            if (lastIndex >= 0) {
                filename = filename.substring(lastIndex + 1);
            }
            document.getElementById('filename').value = filename;
}


    </script>
<div>
<form method="post" action="{$_SERVER['PHP_SELF']}" enctype="multipart/form-data">
<ul class="nav nav-tabs">
    <li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
    <li><a href="contact.html">Contact Us</a></li>

</ul>
        <div style="padding-left: 38em; padding-right: 38em">
            <h2>Register New Account</h2>
            <strong>Name: </strong>
            <input type="text" name="name" required><br><br>
            <strong>Email: </strong>
            <input type="email" name="email" required><br><br>
            <strong>Phone Number: </strong>
            <input type="text" name="phone_validation" pattern="\([0-9]{3}\)[0-9]{3}[\-][0-9]{4}$" required 
            title="Please enter in form: (123)456-7890" placeholder="(123)456-7890"><br><br>

            
            <div class="custom-select" style="width: 200px">
                <strong>Gender: </strong>
                <select name="gender" size="1" required >
                    <option value="N">Prefer Not Answer</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select><br><br>
            </div><br>


            <strong>Password: </strong>
            <input type="password" name="pwd" id="pwd" onkeyup="check()" required><br><br>
            <strong>Verify Password: </strong>
            <input type="password" name="verifyPwd" id="verifyPwd" onkeyup="check()" required>
            <span id="message"></span><br><br>


            <strong>Image to upload: </strong>
            <input type="file" name="image" id="image" accept="image/*">
            <input type="hidden" name="MAX_FILE_SIZE" value="300000" >


            <input type="submit" name="submit"  value="Register" style="color: white" ><br><br>
            <button onclick="location.href='index.html'">Return to main menu</button><br />
        </div>
   </form>
 
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


$bot="";
if (isset($_POST['submit'])) {
    if ($_POST['pwd'] !== $_POST['verifyPwd']) {
        $upper = "<div style=\"padding-left: 30em; padding-right: 30em\"><h3 style='text-align: center;'>Password Does Not Match</h3>";
        $upper .= "<script>function goBack() {window.history.back();}</script>
                <button onclick='goBack()'>Go Back</button>";
    } else {
        $table = "users";
        $hashed = password_hash(trim($_POST["pwd"]), PASSWORD_DEFAULT);
        $db = connectToDB();

        $image_name = $_FILES['image']['name'];

        $Name = trim($_POST['name']);
        $Email = trim($_POST['email']);
        $Phone = trim($_POST['phone_validation']);
        $Gender = trim($_POST['gender']);

        $imagetmp= addslashes(file_get_contents($_FILES['image']['tmp_name']));

        // $imagetmp = $_FILES['image']['tmp_name'];

        $serverUploadDirectory = "C:\\xampp\htdocs\CMSC389N\groupproject\image"; 

        $tmpFileName = $_FILES['image']['tmp_name'];
        $serverFileName = $serverUploadDirectory."/".$_FILES['image']['name'];

        move_uploaded_file($tmpFileName, $serverFileName);

        // $sqlQuery = sprintf("insert into $table (name,email,tel,gender,password) values ('%s','%s','%s','%s','%s','%b')",
        //     trim($_POST['name']), trim($_POST['email']), trim($_POST['phone_validation']), trim($_POST['gender']), $hashed, $imagetmp);

        $sqlQuery = "insert into $table values('$Name', '$Email', '$Phone', '$Gender', '$hashed', '$imagetmp')";

        $result = mysqli_query($db, $sqlQuery);

        if ($result) {
            $upper = "<div style=\"padding-left: 30em; padding-right: 30em\"><h3>Thank you for register, please go back to main page and login</h3>";
            $upper .= "<a href='index.html'><button>Return to main menu</button></a></div>";
        } else {
            die("error here".$db->error);
            $upper = "<div style=\"padding-left: 30em; padding-right: 30em\"><h3 style='text-align: center'>Email has already been used</h3>";
            $upper .= "<script>function goBack() {window.history.back();}</script>
                <button onclick='goBack()'>Go Back</button>";
        }

        mysqli_close($db);

    }
}

echo generatePage($upper.$bot, "Register");