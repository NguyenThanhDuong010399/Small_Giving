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

    if(isset($data->NoiDung)){
        // CHECK DATA VALUE IS EMPTY OR NOT
        if($data->NoiDung!=""){
            $post="";
            $insert_query = "INSERT INTO tblgopy(NoiDung) VALUES(:NoiDung)";
            
            $insert_stmt = $conn->prepare($insert_query);
            // DATA BINDING
            // $insert_stmt->bindValue(':TenNguoiDung', htmlspecialchars(strip_tags($data->TenNguoiDung)),PDO::PARAM_STR);
            
            $insert_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($data->NoiDung)),PDO::PARAM_STR);
            // $insert_stmt->bindValue(':post', htmlspecialchars(strip_tags($post)),PDO::PARAM_STR);
            if($insert_stmt->execute()){
                $msg['message'] = 'Success';
            }else{
                $msg['message'] = 'Faile';
            } 
            
        }else{
            $msg['message'] = 'Noi dung khong duoc de trong';
        }
    }
    else{
        $msg['message'] = 'Noi dung khong duoc de trong';
    }
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>