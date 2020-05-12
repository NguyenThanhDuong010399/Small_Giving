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
if(isset($data->idHoatDong))
{
    if($data->idHoatDong!="")
    {
        $sql="SELECT * from tblhoatdong where idHoatDong=:idHoatDong";
        $stmt=$conn->prepare($sql);
        if($stmt->rowCount()>0)
        {
            $msg['message'] = 'Tai khoan da ton tai';
        }
        else{
            
            $post_ngaybd=isset($data->ThoiGianBD)?date("Y-m-d", strtotime($data->NgaySinh)):current_timestamp();
            $post_ngaykt=isset($data->ThoiGianKT)?date("Y-m-d", strtotime($data->ThoiGianKT)):'';
            $post_ngaykt=isset($data->ThoiGianKT)?date("Y-m-d", strtotime($data->ThoiGianKT)):'';
            $post_sodudk=isset($data->SoDuTK)?$data->ThoiGianKT:'0';
            $post_songuoi=isset($data->SoNguoi)?$data->SoNguoi:'0';
            $post_nhansu=isset($data->NhanSuDK)?$data->NhanSuDK:'0';
            $insert_query = "INSERT INTO tblhoatdong(TenHoatDong, NoiDung, ThoiGianBD, ThoiGianKT, DiaChi, Anh, SoDuTK, SoNguoi, NhanSuDK, ChiDK, TongNhanSu, TongChi) VALUES(:TenHoatDong,:NoiDung,:ThoiGianBD, :ThoiGianKT, :DiaChi, :Anh, :SoDuTK, :SoNguoi, :NhanSuDK, :ChiDK, :TongNhanSu, :TongChi)";
    
            $insert_stmt = $conn->prepare($insert_query);
            // DATA BINDING
            $insert_stmt->bindValue(':TenHoatDong', htmlspecialchars(strip_tags($data->TenHoatDong)),PDO::PARAM_INT);
            $insert_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($data->NoiDung)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':ThoiGianBD', htmlspecialchars(strip_tags($post_ngaybd)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':ThoiGianKT', htmlspecialchars(strip_tags($post_ngaykt)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':DiaChi', htmlspecialchars(strip_tags($data->DiaChi)),PDO::PARAM_INT);
            $insert_stmt->bindValue(':Anh', htmlspecialchars(strip_tags($data->Anh)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':SoDuTK', htmlspecialchars(strip_tags($post_sodudk)),PDO::PARAM_INT);
            $insert_stmt->bindValue(':SoNguoi', htmlspecialchars(strip_tags($post_songuoi)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':NhanSuDK', htmlspecialchars(strip_tags($post_sodudk)),PDO::PARAM_INT);
            $insert_stmt->bindValue(':ChiDK', htmlspecialchars(strip_tags($data->ChiDK)),PDO::PARAM_STR);
           
            
            if($insert_stmt->execute()){
                $msg['message'] = 'success';
            }else{
                $msg['message'] = 'Faile';
            } 
        }
    }
    else{
        $msg['message'] = 'Khong duoc de trong id hoat dong';
    }
}
else{
    $msg['message'] = 'ID khong ton tai';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>