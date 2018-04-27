<?php
    require("support.php");

    $body = <<<EOBODY
    <form action="{$_SERVER['PHP_SELF']}" method="post" class = "form-horizontal">
    <ul class="nav nav-tabs">
        <li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
        <li><a href="contact.php">Contact Us</a></li>
    
    </ul>
    
        <div id = 'cent'>
            <h1>Input your avaible date</h1>
            <strong>Name: </strong>
                <input type="text" name="name"  required/></br></br>
            <strong>Date: </strong>
                <input type='date'><span class="glyphicon glyphicon-calendar"></span>

            <input type="submit" name="submitDate" value = "submit Time"></br>
        </div>
    </form>
EOBODY;

$page = generatePage($body, "group");
echo $page;
?>