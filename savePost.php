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
	$post = mysqli_real_escape_string($conn,$data['post']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	
	if (validateUser($username,$userId)) {
		if(trim($post) != ''){
			$q = mysqli_query($conn, "insert into posts(username,post) values('$username','$post')");
			if($q){
				//mark all files as used
				//TODO!
				$obj = new StdClass();
				$obj->msg = "Your post has been saved successful.";
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
			$obj->msg= "Sever rejected to save the post because it is empty";
	        $obj->type= "error";
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