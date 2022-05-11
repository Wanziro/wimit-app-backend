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
	$postId = mysqli_real_escape_string($conn,$data['postId']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	$commentId = mysqli_real_escape_string($conn,$data['commentId']);
	
	if (validateUser($username,$userId)) {
		$q = mysqli_query($conn, "delete from post_comments where username='$username' and post_id='$postId' and id='$commentId'");
		if($q){	
			mysqli_query($conn, "delete from post_comments_likes where username='$username' and post_id='$postId' and comment_id='$commentId'");
			mysqli_query($conn, "delete from post_comments_dislikes where username='$username' and post_id='$postId' and comment_id='$commentId'");		
			updatePostCommentsNum($postId);
			$obj = new StdClass();
			$obj->msg = "Comment removed successful.";
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