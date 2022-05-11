<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

$Arr = array();
$qx = mysqli_query($conn, "select * from shorts order by id desc");
while ($row = mysqli_fetch_assoc($qx)) {
	$id = $row['id'];
	$username = $row['username'];

	$obj = new StdClass();
	$obj->id = $row['id'];
	$obj->owner = userObject($username);
	$obj->video = $row['video'];
	$obj->width = $row['width'];
	$obj->height = $row['height'];
	$obj->caption = $row['caption'];
	$obj->comments = $row['comments'];
	$obj->likes = $row['likes'];
	$obj->dislikes = $row['dislikes'];
	$obj->date = $row['date'];

	array_push($Arr, $obj);
}
echo json_encode($Arr);

?>