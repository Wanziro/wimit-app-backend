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
	if (validateUser($username,$userId)) {
		$Arr = array();
		// $q = mysqli_query($conn, "select * from users where username !='$username'");
		$q = mysqli_query($conn, "select * from users");
		if(mysqli_num_rows($q) > 0){
			while($row = mysqli_fetch_assoc($q)){
				//user object
				$userObj = new StdClass();
				$userObj->fname = $row['fname'];
				$userObj->lname = $row['lname'];
				$userObj->id = $row['id'];
				$userObj->phone = $row['phone'];
				$userObj->email = $row['email'];
				$userObj->username = $row['username'];
				$userObj->image = $row['image'];
				//user object
				array_push($Arr, $userObj);
			}
		}
		echo json_encode($Arr);
	}else{
		$obj = new StdClass();
		$obj->msg= "Invalid credentials. server cant handle this request";
        $obj->type= "error";
        echo json_encode($obj);
	}
}
?>