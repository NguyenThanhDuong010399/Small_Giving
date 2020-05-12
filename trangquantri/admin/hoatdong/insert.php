<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../../../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if($data->TenHoatDong!="")
{
    
  $insert_query = "INSERT INTO tbl_hoat_dong(TenHoatDong,NoiDung,ThoiGianBD,ThoiGianKT,DiaChi,Anh, SoNguoiTG, ChiDK) VALUES(:TenHoatDong,:NoiDung,:ThoiGianBD,:ThoiGianKT,:DiaChi,:Anh,'0',:ChiDK)";

    $insert_stmt = $conn->prepare($insert_query);
    // DATA BINDING
    $insert_stmt->bindValue(':TenHoatDong', htmlspecialchars(strip_tags($data->TenHoatDong)),PDO::PARAM_STR);
    $insert_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($data->NoiDung)),PDO::PARAM_STR);
    $insert_stmt->bindValue(':ThoiGianBD', htmlspecialchars(strip_tags($data->ThoiGianBD)),PDO::PARAM_STR);
    $insert_stmt->bindValue(':ThoiGianKT', htmlspecialchars(strip_tags($data->ThoiGianKT)),PDO::PARAM_STR);
    $insert_stmt->bindValue(':DiaChi', htmlspecialchars(strip_tags($data->DiaChi)),PDO::PARAM_STR);
    $insert_stmt->bindValue(':Anh', htmlspecialchars(strip_tags($data->Anh)),PDO::PARAM_STR);
    // $insert_stmt->bindValue(':SoDuTK', htmlspecialchars(strip_tags($data->SoDuTK)),PDO::PARAM_STR);
    // $insert_stmt->bindValue(':SoNguoi', htmlspecialchars(strip_tags($data->SoNguoi)),PDO::PARAM_STR);
    $insert_stmt->bindValue(':ChiDK', htmlspecialchars(strip_tags($data->ChiDK)),PDO::PARAM_STR);

    if($insert_stmt->execute()){
        $msg['message'] = 'success';
    }else{
        $msg['message'] = 'Faile';
    } 
}
else{
    $msg['message'] = 'Khong duoc de trong ten';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>