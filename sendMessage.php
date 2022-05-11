<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';

if (isset($data["sender"])) {
	$sender = mysqli_real_escape_string($conn,$data['sender']);
	$receiver = mysqli_real_escape_string($conn,$data['receiver']);
	$textMessage = mysqli_real_escape_string($conn,$data['textMessage']);
	$file = mysqli_real_escape_string($conn,$data['file']);
	$repliedMessage = mysqli_real_escape_string($conn,$data['repliedMessage']);
	$date = mysqli_real_escape_string($conn,$data['date']);
	$uuid = mysqli_real_escape_string($conn,$data['uuid']);

	if (trim($sender) == '' || trim($receiver) == '') {
		$obj = new StdClass();
		$obj->msg = "Invalid request, please provide correct data";
		$obj->type = "error";
		echo json_encode($obj);
	} else{
		$qq = mysqli_query($conn, "INSERT INTO messages(uuid,sender, receiver,repliedMessage, textMessage,file, sent, delivered, seen, date) VALUES ('$uuid','$sender', '$receiver', '$repliedMessage', '$textMessage','$file', 'true', 'false', 'false', '$date')");
		if ($qq) {	
			//get the id of message sent
			$get = mysqli_query($conn,"SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA='wemiti' AND TABLE_NAME='messages'");
			while($row_id = mysqli_fetch_assoc($get)){
				$id = $row_id['AUTO_INCREMENT'];
			}

			$messageSent = new StdClass();
			$messageSent -> id = $id - 1;
			$messageSent -> uuid = $uuid;
			$messageSent -> sender = $sender;
        	$messageSent -> receiver = $receiver;
        	$messageSent -> textMessage = $textMessage;
        	$messageSent -> repliedMessage = $repliedMessage;
        	$messageSent -> file = $file;
        	$messageSent -> date = $date;
        	$messageSent -> sent = 'true';
        	$messageSent -> delivered = 'false';
        	$messageSent -> seen = 'false';

			$obj = new StdClass();
			$obj->msg= "Message has been sent successful.";
            $obj->type= "success";
            $obj->messageSent = $messageSent;
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