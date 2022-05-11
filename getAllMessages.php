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
		$q = mysqli_query($conn, "select * from messages where sender='$username' or receiver='$username' order by id desc");
		if(mysqli_num_rows($q) > 0){	
			while($row = mysqli_fetch_assoc($q)){
				$messageSent = new StdClass();
				$messageSent -> id = $row['id'];
				$messageSent -> uuid = $row['uuid'];
				$messageSent -> sender = $row['sender'];
				$messageSent -> senderImage = getUserImage($row['sender']);
	        	$messageSent -> receiver = $row['receiver'];
	        	$messageSent -> receiverImage = getUserImage($row['receiver']);
	        	$messageSent -> receiver = $row['receiver'];
	        	$messageSent -> textMessage = $row['textMessage'];
	        	$messageSent -> repliedMessage = $row['repliedMessage'];
	        	$messageSent -> file = $row['file'];
	        	$messageSent -> date = $row['date'];
	        	$messageSent -> sent = $row['sent'];
	        	$messageSent -> delivered = $row['delivered'];
	        	$messageSent -> seen = $row['seen'];

	        	array_push($Arr, $messageSent);
			}
		}

		$obj = new StdClass();
		$obj->msg = "Retrieved user messages successfull.";
		$obj->type = "success";
		$obj->messages = $Arr;
		echo json_encode($obj);
	}else{
		$obj = new StdClass();
		$obj->msg= "Invalid credentials. server cant handle this request";
        $obj->type= "error";
        echo json_encode($obj);
	}
}
?>