<?php
date_default_timezone_set('Africa/Kigali');
$conn = mysqli_connect("localhost","root","","wemiti");
if(!$conn){
	echo "Failed to connect to the server";
	exit();
}
?>