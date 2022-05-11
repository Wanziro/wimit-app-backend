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
	
	if (validateUser($username,$userId)) {
		$q = mysqli_query($conn, "select * from posts where username='$username' and id='$postId'");
		if(mysqli_num_rows($q) == 1){
			while ($row = mysqli_fetch_assoc($q)) {
				$post = json_decode($row['post'], true);
				for($i = 0; $i < count($post['images']); $i++){
					$folder = 'uploads/posts/';
					if(file_exists($folder.$post['images'][$i])){
						unlink(file_exists($folder.$post['images'][$i]));
					}
				}
				//TODO
				//delete a video
				mysqli_query($conn, "delete from posts where username='$username' and id='$postId'");
				mysqli_query($conn, "delete from post_comments where post_id='$postId'");
				mysqli_query($conn, "delete from post_comments_dislikes where post_id='$postId'");
				mysqli_query($conn, "delete from post_comments_likes where post_id='$postId'");
				mysqli_query($conn, "delete from post_dislikes where post_id='$postId'");
				mysqli_query($conn, "delete from post_likes where post_id='$postId'");
			}
		}else{
			$obj = new StdClass();
			$obj->msg= "Post does not exist";
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