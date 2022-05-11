<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

if (isset($data["username"])) {
	$username = mysqli_real_escape_string($conn,$data['username']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	$caption = mysqli_real_escape_string($conn,$data['caption']);
	$video = mysqli_real_escape_string($conn,$data['video']);
	$width = mysqli_real_escape_string($conn,$data['width']);
	$height = mysqli_real_escape_string($conn,$data['height']);
	$date = mysqli_real_escape_string($conn,$data['date']);
	
	if (validateUser($username,$userId)) {
		$q = mysqli_query($conn, "insert into shorts(video,width,height,caption,username,date) values('$video','$width','$height','$caption','$username','$date')");
		if($q){
			$obj = new StdClass();
			$obj->msg = "Short has been saved successful.";
			$obj->type = "success";
			echo json_encode($obj);
		}else{
			$obj = new StdClass();
			$obj->msg = "Something went wrong. Try again later after some time.";
			$obj->type = "error";
			echo json_encode($obj);
		}
	}else{
		$obj = new StdClass();
		$obj->msg= "Invalid credentials. server cant handle this request";
        $obj->type= "error";
        echo json_encode($obj);
	}
}
?>