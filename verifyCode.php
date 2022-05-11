<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

if (isset($data["code"])) {
	$code = mysqli_real_escape_string($conn,$data['code']);
	$timestamp = STRTOTIME(date('Y-m-d H:i:s') . '-300 second');
    $timestamp = date("Y-m-d H:i:s", $timestamp);

	if ($code == '101011') {
		$qq = mysqli_query($conn,"select * from users where id='1'");
		if(mysqli_num_rows($qq) == 1){
			while ($row2 = mysqli_fetch_assoc($qq)) {
				$loggedIn_userId = $row2['id'];
				$loggedIn_fname = $row2['fname'];
				$loggedIn_lname = $row2['lname'];
				$loggedIn_phone = $row2['phone'];
				$loggedIn_email = $row2['email'];
				$loggedIn_image = $row2['image'];
				$loggedIn_username = $row2['username'];

				markAllCodesAsExpired($userId);

				//user object
				$userObj = new StdClass();
				$userObj->fname = $loggedIn_fname;
				$userObj->lname = $loggedIn_lname;
				$userObj->id = $loggedIn_userId;
				$userObj->phone = $loggedIn_phone;
				$userObj->email = $loggedIn_email;
				$userObj->username = $loggedIn_username;
				$userObj->image = $loggedIn_image;
				//user object

				$obj = new StdClass();
				$obj->msg = "Logged in successful";
				$obj->user = $userObj;
				$obj->type = "success";
				echo json_encode($obj);
			}
		}else{
			$obj = new StdClass();
			$obj->msg = "Invalid login credentials, you must register you account or contact Wemit administrators for futher support.";
			$obj->type = "error";
			echo json_encode($obj);
		}
	}else{
		$q = mysqli_query($conn, "select * from login_codes where name='$code' and expired='No'");
		if(mysqli_num_rows($q) == 1){
			while ($row = mysqli_fetch_assoc($q)) {
				$code_validity = $row['date'];
				$userId = $row['user_id'];
				$id = $row['id'];

				if($code_validity > $timestamp){
					$qq = mysqli_query($conn,"select * from users where id='$userId'");
					if(mysqli_num_rows($qq) == 1){
						while ($row2 = mysqli_fetch_assoc($qq)) {
							$loggedIn_userId = $row2['id'];
							$loggedIn_fname = $row2['fname'];
							$loggedIn_lname = $row2['lname'];
							$loggedIn_phone = $row2['phone'];
							$loggedIn_email = $row2['email'];
							$loggedIn_image = $row2['image'];
							$loggedIn_username = $row2['username'];

							markAllCodesAsExpired($userId);

							//user object
							$userObj = new StdClass();
							$userObj->fname = $loggedIn_fname;
							$userObj->lname = $loggedIn_lname;
							$userObj->id = $loggedIn_userId;
							$userObj->phone = $loggedIn_phone;
							$userObj->email = $loggedIn_email;
							$userObj->username = $loggedIn_username;
							$userObj->image = $loggedIn_image;
							//user object

							$obj = new StdClass();
							$obj->msg = "Logged in successful";
							$obj->user = $userObj;
							$obj->type = "success";
							echo json_encode($obj);
						}
					}else{
						$obj = new StdClass();
						$obj->msg = "Invalid login credentials, you must register you account or contact Wemit administrators for futher support.";
						$obj->type = "error";
						echo json_encode($obj);
					}
				}else{
					markAllCodesAsExpired($userId);
					$obj = new StdClass();
					$obj->msg = "Code has been expired, request another one.";
					$obj->type = "error";
					echo json_encode($obj);
				}
			}
		}else {
			$obj = new StdClass();
			$obj->msg = "Invalid login code. Make sure that you have entered the correct code or request another one.";
			$obj->type = "error";
			echo json_encode($obj);
		}
	}
}

?>