<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../database/database.php';
include_once '../index.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$Dangnhap = new Dangnhap($db);
 
// set ID property of record to read
$Dangnhap->id = isset($_GET['id']) ? $_GET['id'] : die();
//$Dangnhap->SDT = isset($_GET['SDT']) ? $_GET['SDT'] : die();
 
// read the details of product to be edited
$Dangnhap->Nhopass();
 
if($Dangnhap->id!=null){
    
    // set response code - 404 Not found
    $product_arr = array(
        //"Email"=> $Dangnhap->Email,
        "MatKhau" =>  $Dangnhap->MatKhau
        // ,
        // "TenSanPham" => $product->TenSanPham,
        // "NgayNhan" => $product->NgayNhan
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($product_arr);
}
 
else{
    
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "false."));
    // create array
}
?>