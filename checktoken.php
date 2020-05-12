<?php 
// $token=$_GET['token'];
// require "jwt.php";
// $json=JWT::decode($token,"Small_Giving",true);
// echo json_encode($json);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
 require 'jwt.php';
 $data = json_decode(file_get_contents("php://input"));
 if(isset($data->token))
{
	$json=JWT::decode($data->token,"Small_Giving",true);
	echo json_encode($json);
}
else
{
	$msg['token'] = 'Error';
	echo json_encode($msg);
}
?>