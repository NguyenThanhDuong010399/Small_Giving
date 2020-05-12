<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../database/database.php';
include_once '../index.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$doipass = new Dangnhap($db);
 
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$doipass->MatKhau = $data->MatKhau;
//$product->NgayNhan = $data->NgayNhan;
// $product->description = $data->description;
$doipass->id = $data->id;
 
// update the product
if($doipass->Doipass()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "success."));
}
 
// if unable to update the product, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Faile."));
}
?>