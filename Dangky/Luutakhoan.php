<?php
// // required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../database/database.php';
 
// instantiate product object
include_once '../index.php';
 
$database = new Database();
$db = $database->getConnection();
 
$Luu = new Luutaikhoan($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"),true);
    
    $Luu->Email = $data['Email'];
    $Luu->SDT = $data['SDT'];
    $Luu->MatKhau = $data['MatKhau'];

    if($Luu->Luutaikhoan()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Luu thanh cong."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Luu that bai."));
    }
?>