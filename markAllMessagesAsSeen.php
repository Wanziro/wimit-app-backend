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

	if (trim($sender) == '' || trim($receiver) == '') {
		$obj = new StdClass();
		$obj->msg = "Invalid request, please provide correct data";
		$obj->type = "error";
		echo json_encode($obj);
	} else{
		$qq = mysqli_query($conn, "UPDATE messages SET delivered='true',seen='true' WHERE receiver='$receiver' AND sender='$sender'");
		if ($qq) {	
			$obj = new StdClass();
			$obj->msg= "All messages marked as seen.";
            $obj->type= "success";
            echo json_encode($obj);
		}else{
			$obj = new StdClass();
			$obj->msg= "Something went wrong while marking all messages as seen. Try again later after some time.";
            $obj->type= "error";
            $obj->error = "Failed to execute the query";
            echo json_encode($obj);
		}
	}
}

?>