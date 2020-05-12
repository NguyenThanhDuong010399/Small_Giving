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
if(isset($data->idNguoiDung)){
$msg['message'] = '';
$post_idNguoiDung=$data->idNguoiDung;
$sql="SELECT idNguoiDung FROM tblnguoidung where idNguoiDung=:post_idNguoiDung";
    $get_stmt = $conn->prepare($sql);
    $get_stmt->bindValue(':post_idNguoiDung', $post_idNguoiDung,PDO::PARAM_INT);
    $get_stmt->execute();
// CHECK IF RECEIVED DATA FROM THE REQUEST
if($get_stmt->rowCount()>0)
{
    $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
    // $data->idNguoiDung=$row['idNguoiDung'];
    if(isset($data->NoiDung)){
        // CHECK DATA VALUE IS EMPTY OR NOT
        if($data->NoiDung!=""){
            
            $insert_query = "INSERT INTO tblgopy(NoiDung,idNguoiDung) VALUES(:NoiDung,:idNguoiDung)";
            
            $insert_stmt = $conn->prepare($insert_query);
            // DATA BINDING
            // $insert_stmt->bindValue(':TenNguoiDung', htmlspecialchars(strip_tags($data->TenNguoiDung)),PDO::PARAM_STR);
            
            $insert_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($data->NoiDung)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($data->idNguoiDung)),PDO::PARAM_INT);
            
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
}
else{
    $msg['message'] = 'Tai khoan khong ton tai';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
}
?>