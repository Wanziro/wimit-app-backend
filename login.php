<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';

function sendSMS($phone,$message) {
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.mista.io/sms',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => array('to' => $phone,'from' => 'FELIXPRO','unicode' => '0','sms' => $message,'action' => 'send-sms'),
	  CURLOPT_HTTPHEADER => array(
	    'x-api-key: 4917faa3-c2a1-980d-be49-22e0c53d1c9b-38cadf23'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}

// function sendEmail($email,$message){
// 	include 'connect.php';
// 	$to      = $email;
// 	$subject = 'Wimit login code.';
// 	$message = $message;
// 	$headers = 'From: no-reply@wimit.com' . "\r\n" .
// 	    'X-Mailer: PHP/' . phpversion();
// 	if(mail($to, $subject, $message, $headers)){
// 		return 'sent';
// 	}else{
// 		return 'Not sent';
// 	}
// }

if (isset($data["phone"])) {
	$phone = mysqli_real_escape_string($conn,$data['phone']);
	$appHash = mysqli_real_escape_string($conn,$data['appHash']);
	$q = mysqli_query($conn, "select * from users where email='$phone' or phone='$phone'");
	if(mysqli_num_rows($q) == 1){
		while ($row = mysqli_fetch_assoc($q)) {
			$registered_phone = $row['phone'];
			$registered_email = $row['email'];
			$id = $row['id'];

			//code
			$code = rand(111111,999999);
			//code
			$dot = ".";
			$emailMessage = "Your Wimiti login code is: $code$dot";
			$message = "Your Wimiti login code is: $code$dot \n\n$appHash";

			$response = sendSMS($registered_phone,$message);
			$result = json_decode($response, true);

			// $emailStatus = sendEmail($registered_email,$emailMessage);
			// $emailStatus = 'hey';

			if($result['code']){
				if ($result['code'] == 'ok') {					
					$date = date("Y-m-d H:i:s", STRTOTIME(date('h:i:sa')));
					mysqli_query($conn, "insert into login_codes(user_id,name,date) values('$id','$code','$date')");
					$obj = new StdClass();
					$obj->msg= "Login code sent to $phone successfully. Please do not share it with anyone else.";
			        $obj->type= "success";

			        echo json_encode($obj);
				}else{
					$obj = new StdClass();

					$obj->msg= "Failed to send login code. Try again later after some time.";
			        $obj->type= "error";
			        echo json_encode($obj);
				}
			}
		}	
	}else{
		$obj = new StdClass();

		$obj->msg= "Invalid phone number. Enter correct phone number or register a new account.";
        $obj->type= "error";
        echo json_encode($obj);
	}
}

?>