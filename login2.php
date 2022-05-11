<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';

if (isset($data["username"])) {
	$username = mysqli_real_escape_string($conn,$data['username']);
	$password = mysqli_real_escape_string($conn,$data['password']);
	$q = mysqli_query($conn, "select * from users where username='$username' and password='".md5($password)."'");
	if(mysqli_num_rows($q) == 1){
		while ($row = mysqli_fetch_assoc($q)) {
			$loggedIn_userId = $row['id'];
			$loggedIn_fname = $row['fname'];
			$loggedIn_lname = $row['lname'];
			$loggedIn_phone = $row['phone'];
			$loggedIn_email = $row['email'];
			$loggedIn_image = $row['image'];
			$loggedIn_username = $row['username'];

			// markAllCodesAsExpired($userId);

			//user object
			$userObj = new StdClass();
			$userObj->fname = $loggedIn_fname;
			$userObj->lname = $loggedIn_lname;
			$userObj->id = $loggedIn_userId;
			$userObj->phone = $loggedIn_phone;
			$userObj->email = $loggedIn_email;
			$userObj->username = $loggedIn_username;
			$userObj->image = $loggedIn_image;
			//user object

			$obj = new StdClass();
			$obj->msg = "Logged in successful";
			$obj->user = $userObj;
			$obj->type = "success";
			echo json_encode($obj);
		}	
	}else{
		$obj = new StdClass();
		$obj->msg= "Invalid username or password.";
        $obj->type= "error";
        echo json_encode($obj);
	}
}

?>