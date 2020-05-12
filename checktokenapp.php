<?php 
$token=$_GET['token'];
require "jwt.php";
$json=JWT::decode($token,"Small_Giving",true);
echo json_encode($json);
?>