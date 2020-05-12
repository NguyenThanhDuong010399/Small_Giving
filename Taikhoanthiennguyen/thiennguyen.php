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
if(isset($data->TenHoatDong) && isset($data->NoiDung) && isset($data->ThoiGianBD)&& isset($data->ThoiGianKT)&& isset($data->DiaChi)&& isset($data->Anh)&& isset($data->Video)&& isset($data->ChiDK)){
    // CHECK DATA VALUE IS EMPTY OR NOT
    if(!empty($data->TenHoatDong) && !empty($data->NoiDung) && !empty($data->ThoiGianBD)&& !empty($data->ThoiGianKT)&& !empty($data->DiaChi)&& !empty($data->Anh)&& !empty($data->Video)&& !empty($data->ChiDK)){
        $data->SoDuTK=0;
        $data->SoNguoi=0;
        $insert_query = "INSERT INTO tblhoatdong(TenHoatDong,NoiDung,ThoiGianBD,ThoiGianKT,DiaChi,Anh,Video,SoDuTK,SoNguoi,ChiDK) VALUES(:TenHoatDong,:NoiDung,:ThoiGianBD,:ThoiGianKT,:DiaChi,:Anh,:Video,:SoDuTK,:SoNguoi,:ChiDK)";
        
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':TenHoatDong', htmlspecialchars(strip_tags($data->TenHoatDong)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($data->NoiDung)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':ThoiGianBD', htmlspecialchars(strip_tags($data->ThoiGianBD)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':ThoiGianKT', htmlspecialchars(strip_tags($data->ThoiGianKT)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':DiaChi', htmlspecialchars(strip_tags($data->DiaChi)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':Anh', htmlspecialchars(strip_tags($data->Anh)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':Video', htmlspecialchars(strip_tags($data->Video)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':SoDuTK', htmlspecialchars(strip_tags($data->SoDuTK)),PDO::PARAM_INT);
        $insert_stmt->bindValue(':SoNguoi', htmlspecialchars(strip_tags($data->SoNguoi)),PDO::PARAM_INT);
        $insert_stmt->bindValue(':ChiDK', htmlspecialchars(strip_tags($data->ChiDK)),PDO::PARAM_INT);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Success';
        }else{
            $msg['message'] = 'Faile';
        } 
        
    }else{
        $msg['message'] = 'Khong duoc de trong 1 trong cac truong';
    }
}
else{
    $msg['message'] = 'Khong duoc de trong 1 trong cac truong';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>