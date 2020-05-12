<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if(isset($data->TenTK)){
    // CHECK DATA VALUE IS EMPTY OR NOT
    if(!empty($data->TenTK)){
        $data->SoDuTK=0;
        $data->SoNguoiTG=0;
        $insert_query = "INSERT INTO tbldiemdanh(TenTK,SoDuTK,SoNguoiTG) VALUES(:TenTK,:SoDuTK,:SoNguoiTG)";
        
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':TenTK', htmlspecialchars(strip_tags($data->TenTK)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':SoDuTK', htmlspecialchars(strip_tags($data->SoDuTK)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':SoNguoiTG', htmlspecialchars(strip_tags($data->SoNguoiTG)),PDO::PARAM_STR);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Success';
        }else{
            $msg['message'] = 'Faile';
        } 
        
    }else{
        $msg['message'] = 'khong ton tai';
    }
}
else{
    $msg['message'] = 'Khong ton tai';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>
