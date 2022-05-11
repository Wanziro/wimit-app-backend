<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

if (isset($data["postId"])) {
	$postId = $data["postId"];
	$q = mysqli_query($conn, "select * from post_comments where post_id='$postId'");
	$Arr = array();
	while ($row = mysqli_fetch_assoc($q)) {
		$id = $row['id'];
		$username = $row['username'];

		$obj = new StdClass();
		$obj->id = $row['id'];
		$obj->owner = userObject($username);
		$obj->comment = $row['comment'];
		$obj->date = $row['date'];
		$obj->likes = $row['likes'];
		$obj->dislikes = $row['dislikes'];
		$obj->postId = $row['post_id'];
		array_push($Arr, $obj);
	}
	echo json_encode($Arr);
}
?>