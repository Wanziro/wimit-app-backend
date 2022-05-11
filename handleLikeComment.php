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
		$q = mysqli_query($conn, "select * from post_comments_likes where username='$username' and post_id='$postId' and comment_id='$commentId'");
		if(mysqli_num_rows($q) == 0){
			$qq = mysqli_query($conn,"insert into post_comments_likes(post_id,username,comment_id) values('$postId','$username','$commentId')");
			updatePostCommentsLikes($commentId);
			if($qq){
				mysqli_query($conn,"delete from post_comments_dislikes where comment_id='$commentId' and username='$username' and post_id='$postId'");
				updatePostCommentsDislikes($commentId);
			}
			$obj = new StdClass();
			$obj->msg = "Liked the post comment successful.";
			$obj->type = "success";
			echo json_encode($obj);
		}else{
			$qq = mysqli_query($conn,"delete from post_comments_likes where post_id='$postId' and username='$username' and comment_id='$commentId'");
			updatePostCommentsLikes($commentId);
			$obj = new StdClass();
			$obj->msg = "UnLiked the post successful.";
			$obj->type = "success";
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