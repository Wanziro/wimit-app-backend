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
	$image = mysqli_real_escape_string($conn,$data['image']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	
	if (validateUser($username,$userId)) {
		$q = mysqli_query($conn, "update users set image='$image' where username='$username' and id='$userId'");
		if($q){
			$obj = new StdClass();
			$obj->msg = "Image updated successfull";
			$obj->type = "success";
			echo json_encode($obj);
		}else{
			$obj = new StdClass();
			$obj->msg = "Something went wrong, try again later after some time.";
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