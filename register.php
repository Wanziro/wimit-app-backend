<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';

if (isset($data["phone"])) {
	$fname = mysqli_real_escape_string($conn,$data['fname']);
	$lname = mysqli_real_escape_string($conn,$data['lname']);
	$email = mysqli_real_escape_string($conn,$data['email']);
	$phone = mysqli_real_escape_string($conn,$data['phone']);
	$username = mysqli_real_escape_string($conn,$data['username']);
	$password = mysqli_real_escape_string($conn,$data['password']);

	$q = mysqli_query($conn, "select * from users where phone='$phone' or email='$email'");
	$q2 = mysqli_query($conn, "select * from users where username='$username'");
	if(mysqli_num_rows($q) > 0){
		$obj = new StdClass();
		$obj->msg = "Email/Phone already exists.";
		$obj->type = "warning";
		echo json_encode($obj);
	}elseif (mysqli_num_rows($q2) > 0) {
		$obj = new StdClass();
		$obj->msg = "Username already taken. Try another one";
		$obj->type = "warning";
		echo json_encode($obj);
	} else{
		$qq = mysqli_query($conn, "insert into users(fname,lname,phone,email,username,password) values('$fname','$lname','$phone','$email','$username','".md5($password)."')");
		if ($qq) {			
			$obj = new StdClass();

			$obj->msg= "User account has been created successful!";
            $obj->type= "message";
            echo json_encode($obj);
		}else{
			$obj = new StdClass();

			$obj->msg= "Something went wrong. Try again later after some time.";
            $obj->type= "error";
            $obj->error = "Failed to execute the query";
            echo json_encode($obj);
		}
	}
}

?>