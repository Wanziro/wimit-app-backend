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
	$comment = mysqli_real_escape_string($conn,$data['comment']);
	$postId = mysqli_real_escape_string($conn,$data['postId']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	
	if (validateUser($username,$userId)) {
		if(trim($comment) != ''){
			$q = mysqli_query($conn, "insert into post_comments(username,post_id,comment) values('$username','$postId','$comment')");
			if($q){
				updatePostCommentsNum($postId);
				$obj = new StdClass();
				$obj->msg = "comment saved successful.";
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
			$obj->msg= "Sever rejected to save the comment because it is empty";
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