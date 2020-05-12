<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../../../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->idHoatDong)){
    
    $msg['message'] = '';
    $post_id = $data->idHoatDong;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM tbl_hoat_dong WHERE idHoatDong=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $post_TenHoatDong = isset($data->TenHoatDong) ? $data->TenHoatDong : $row['TenHoatDong'];
        $post_idThuHuong = isset($data->idNguoiThuHuong) ? $data->idNguoiThuHuong : $row['idNguoiThuHuong'];
        $post_NoiDung = isset($data->NoiDung) ? $data->NoiDung : $row['NoiDung'];
        $post_ThoiGianBD = isset($data->ThoiGianBD) ? date('Y-m-d',strtotime($data->ThoiGianBD)) : $row['ThoiGianBD'];
        $post_ThoiGianKT = isset($data->ThoiGianKT) ? date('Y-m-d',strtotime($data->ThoiGianKT)) : $row['ThoiGianKT'];
        $post_DiaChi = isset($data->DiaChi) ? $data->DiaChi : $row['DiaChi'];
        $post_Anh = isset($data->Anh) ? $data->Anh : $row['Anh'];
        $post_ChiDK = isset($data->ChiDK) ? $data->ChiDK : $row['ChiDK'];
        
        $update_query = "UPDATE tbl_hoat_dong SET TenHoatDong = :TenHoatDong, idNguoiThuHuong = :idNguoiThuHuong,NoiDung=:NoiDung, ThoiGianBD=:ThoiGianBD, ThoiGianKT=:ThoiGianKT,DiaChi=:DiaChi,Anh=:Anh,ChiDK=:ChiDK
        WHERE idHoatDong = :idHoatDong";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':TenHoatDong', htmlspecialchars(strip_tags($post_TenHoatDong)),PDO::PARAM_STR);
        $update_stmt->bindValue(':idNguoiThuHuong', htmlspecialchars(strip_tags($post_idThuHuong)),PDO::PARAM_STR);
         $update_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($post_NoiDung)),PDO::PARAM_STR);
        $update_stmt->bindValue(':ThoiGianBD', $post_ThoiGianBD,PDO::PARAM_STR);
        $update_stmt->bindValue(':ThoiGianKT', $post_ThoiGianKT,PDO::PARAM_STR);
        $update_stmt->bindValue(':DiaChi', $post_DiaChi,PDO::PARAM_STR);
        $update_stmt->bindValue(':Anh', $post_Anh,PDO::PARAM_STR);
        $update_stmt->bindValue(':ChiDK', $post_ChiDK,PDO::PARAM_STR);
        $update_stmt->bindValue(':idHoatDong', $post_id,PDO::PARAM_STR);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'success';
        }else{
            $msg['message'] = 'Faile';
        }   
        
    }
    else{
        $msg['message'] = 'ID khong ton tai';
    }  
}
else{
    $msg['message'] = 'Error';
}  
    echo  json_encode($msg);
?>