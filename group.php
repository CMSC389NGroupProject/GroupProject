<?php

require_once "support.php";

$message = "<tr><td>No Overlap Yet</td></tr>";
$script = "";

if (isset($_POST['submitDate'])) {
    $nameValue = trim($_POST['name']);
    $dateValue = trim($_POST['date']);
    
    /* Connecting to the database */
    $db_connection = connectToDB();

    
    $query = "SELECT * FROM timeSlots WHERE name='$nameValue' AND date='$dateValue'";
    $result = $db_connection->query($query);
    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
	} else {
        /* Number of rows found */
		$num_rows = $result->num_rows;
		if ($num_rows === 0) {

            $stmt = $db_connection->prepare("INSERT INTO timeSlots VALUES (?, ?)");
            $stmt->bind_param("ss",$nameValue,$dateValue);
            $stmt->execute();


            $stmt->close();
	        /* Closing connection */
		} else {
			$message = "Name and date exist";
        }
    }

    /* Freeing memory */
    $result->close();

    //Find common avaliable time
    $query = "CREATE TEMPORARY TABLE uniqueName 
    select DISTINCT(date), name
    from timeslots
    group by date, name";

    $db_connection->query($query);

    $query = "CREATE TEMPORARY TABLE numOfDate
    select date, count(*) as num
    from uniqueName
    group by date";

    $db_connection->query($query);

    $query = "CREATE TEMPORARY TABLE maxDate
    select max(num) as num
    from numOfDate
    where num > 1";

    $db_connection->query($query);

    $query = "select date
    from numOfDate, maxDate
    where numOfDate.num = maxDate.num";

    $result = $db_connection->query($query);


    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
	} else {
        $num_rows = $result->num_rows;
		if ($num_rows === 0) {
			$message = "<tr><td>No Overlap Yet</td></tr>";
		} else {
            // $message = "<div style = 'width: 30%; margin-left:900px; position:absolute; top:8%; margin-top:-50px;' class='container'>";
            // $message .= "<h2>Common Best Common Date</h2>";
            $message = "";
			for ($row_index = 0; $row_index < $num_rows; $row_index++) {
				$result->data_seek($row_index);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                $date = $row['date'];
                $message .= "<tr><td>$date</td></tr>";
            }
            // $message .= "</div>";
		}
    }

    /* Freeing memory */
    $result->close();
    $db_connection->close();

    
}

    /* Connecting to the database */
    $db_connection = connectToDB();

    //Find common avaliable time
    $query = "CREATE TEMPORARY TABLE uniqueName 
    select DISTINCT(date), name
    from timeslots
    group by date, name";

    $db_connection->query($query);

    $query = "CREATE TEMPORARY TABLE numOfDate
    select date, count(*) as num
    from uniqueName
    group by date";

    $db_connection->query($query);

    $query = "CREATE TEMPORARY TABLE maxDate
    select max(num) as num
    from numOfDate
    where num > 1";

    $db_connection->query($query);

    $query = "select date
    from numOfDate, maxDate
    where numOfDate.num = maxDate.num";

    $result = $db_connection->query($query);


    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
	} else {
        $num_rows = $result->num_rows;
		if ($num_rows === 0) {
			$message = "<tr><td>No Overlap Yet</td></tr>";
		} else {
            $message = "";
			for ($row_index = 0; $row_index < $num_rows; $row_index++) {
				$result->data_seek($row_index);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                $date = $row['date'];
                $message .= "<tr><td>$date</td></tr>";
            }
		}
    }

    /* Freeing memory */
    $result->close();
    $db_connection->close();


if (isset($_POST["resetDate"])) {
    /* Connecting to the database */
    $db_connection = connectToDB();

    $query = "TRUNCATE timeSlots";

    $db_connection->query($query);
    
    $message = "<tr><td>No Overlap Yet</td></tr>";

    /* Freeing memory */
    $db_connection->close();

}



$body = <<<EOBODY
<body onload="main()">
    <ul class="nav nav-tabs">
        <li><a href="index.html"><span class="glyphicon glyphicon-home"></span></a></li>&nbsp;&nbsp;&nbsp;&nbsp;
        <li><a href="logout.php">Log Out</a></li> &nbsp;&nbsp;&nbsp;&nbsp;
        <li><a href="contact.html">Contact Us</a></li>
        
    </ul>
    <form id="calendar" action="{$_SERVER['PHP_SELF']}" method="post">
        <div id = 'cent'>
            <h1>Input your avaible date</h1>
            <strong>Name: </strong>
            <input type="text" name="name" id="name" required/></br></br>
            <strong>Date: </strong>
            <input type='date' id="date" name="date" required><span class="glyphicon glyphicon-calendar"></span>
            
            <input type="submit" name="submitDate" id="submitDate" value = "submit Time"></br>
        </div>
    </form>

    

    
    
    <script>

        "use strict"

        function main() {

            var dt = new Date();

            var dd = dt.getDate();
            var mm = dt.getMonth()+1;
            var yyyy = dt.getFullYear();

            if (dt.getDate() > 10) {
                var dd = dt.getDate();
            } else {
                var dd = "0"+dt.getDate();
            }

            var dateElement = document.getElementById('date');
            dateElement.setAttribute('min', yyyy+"-0"+mm+"-"+dd);
            
            var timeSlots = localStorage.getItem("timeSlots");
            if (timeSlots == null) {
                timeSlots = "<li>Nothing yet</li>"
            } 
            document.getElementById("timeSlots").innerHTML = timeSlots;
            
            /* Using anonymous function as listener */
            document.getElementById("submitDate").onclick = function() {
                if (localStorage.getItem("timeSlots") != null) {
                    var previousTimeSlots = localStorage.getItem('timeSlots');
                } else {
                    var previousTimeSlots = "";
                }
                var timeSlots = document.getElementById("timeSlots").innerHTML;
                var name = document.getElementById("name").value;
                var date = document.getElementById("date").value;
                if (name != "" && date != "") {
                    localStorage.setItem("timeSlots", previousTimeSlots + "<li>" + name + " " + date + "</li>");
                }
            }
            
            document.getElementById("resetDate").onclick = function() {
                localStorage.clear();
                // localStorage.setItem("timeSlots") = null;
            }

        }
    </script>

    <div style='margin-top:-10px;'>
    <h2>Avaiable Date Slots</h2>
    <ol id="timeSlots"></ol>
    </div>
    
    <form id="reset" action="{$_SERVER['PHP_SELF']}" method="post">
        <input style="width:30%;" type="submit" name="resetDate" id="resetDate" value="Reset Date Slots">
    </form>

    <div style = 'width: 30%; margin-left:920px; position:absolute; top:8%; margin-top:-50px;' class='container'>
        <h2>The Best Common Date</h2>
        <table class='table table-striped'>
        <thead><tr> <th>Year-Month-Day</th> </tr></thead>
        <tbody>
            $message
        </tbody>
        </table>
    </div>
</body>
EOBODY;

$page = generatePage($body, "group");
echo $page;


?>
