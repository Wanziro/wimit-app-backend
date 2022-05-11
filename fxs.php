<?php
function markAllCodesAsExpired($userId){
	include 'connect.php';
	mysqli_query($conn, "update login_codes set expired='Yes' where user_id='$userId'");
}

function validateUser($username,$userId){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from users where id='$userId' and username='$username'");
	if (mysqli_num_rows($q) == 1) {
		return true;
	}else{
		return false;
	}
}

function getUserImage($username) {
	include 'connect.php';
	$q = mysqli_query($conn, "select * from users where username='$username'");
	if (mysqli_num_rows($q) == 1) {
		while ($row = mysqli_fetch_assoc($q)) {			 
			return $row['image'];
		}
	}else{
		return null;
	}
}

function updatePostCommentsNum($postId){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from post_comments where post_id='$postId'");
	$num = mysqli_num_rows($q);
	mysqli_query($conn,"update posts set comments='$num' where id='$postId'");
}

function updatePostLikesNum($postId){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from post_likes where post_id='$postId'");
	$num = mysqli_num_rows($q);
	mysqli_query($conn,"update posts set likes='$num' where id='$postId'");
}

function updatePostDislikesNum($postId){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from post_dislikes where post_id='$postId'");
	$num = mysqli_num_rows($q);
	mysqli_query($conn,"update posts set dislikes='$num' where id='$postId'");
}

function updatePostCommentsLikes($commentId){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from post_comments_likes where comment_id='$commentId'");
	$num = mysqli_num_rows($q);
	mysqli_query($conn,"update post_comments set likes='$num' where id='$commentId'");
}

function updatePostCommentsDislikes($commentId){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from post_comments_dislikes where comment_id='$commentId'");
	$num = mysqli_num_rows($q);
	mysqli_query($conn,"update post_comments set dislikes='$num' where id='$commentId'");
}

function userObject($username){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from users where username='$username'");
	if (mysqli_num_rows($q) == 1) {
		while ($row = mysqli_fetch_assoc($q)) {
			$obj = new StdClass();
			$obj->id = $row['id'];
			$obj->fname = $row['fname'];
			$obj->lname = $row['lname'];
			$obj->phone = $row['phone'];
			$obj->email = $row['email'];
			$obj->username = $row['username'];
			$obj->image = $row['image'];
			return $obj;
		}
	}else{
		$obj = new StdClass();
		$obj->id = "null";
		$obj->fname ="null";
		$obj->lname = "null";
		$obj->phone = "null";
		$obj->email = "null";
		$obj->email = "null";
		$obj->image = "null";
		$obj->fakeUsername = $username;
		return $obj;
	}
}
?>