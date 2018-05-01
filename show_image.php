<?php 
require_once "support.php";


$db = connectToDB();

if(isset($_GET['email'])){
	$table = "users";
	$Email = $_GET['email'];

	$query = "SELECT * FROM $table WHERE email ='$Email'";

	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_array(MYSQLI_ASSOC)){
		$imagedata = $row['image'];
	}

	// header("content-type: image/jpeg");
	echo $imagedata;

}else{
	echo "error";
}

mysqli_free_result($result);
mysqli_close($db);


?>