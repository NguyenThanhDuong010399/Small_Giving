<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../../../database/database.php';
$database = new Database();
$conn = $database->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->idNguoiDung)){
    $msg['message'] = '';
    $post_id = $data->idNguoiDung;
    //$post_SDT = $data->SDT;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM tbl_nguoi_dung WHERE idNguoiDung=:post_id ";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
            $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
            // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
            $post_MatKhau = isset($data->MatKhau) ? $data->MatKhau : $row['MatKhau'];
            $post_TenNguoiDung = isset($data->TenNguoiDung) ? $data->TenNguoiDung : $row['TenNguoiDung'];
            $post_MatKhau = isset($data->MatKhau) ? $data->MatKhau : $row['MatKhau'];
            $post_NgaySinh = isset($data->NgaySinh) ? $data->NgaySinh : $row['NgaySinh'];
            $post_STK = isset($data->STK) ? $data->STK : $row['STK'];
            $post_Email = isset($data->Email) ? $data->Email : $row['Email'];
            // $post_SDT = isset($data->SDT) ? $data->SDT : $row['SDT'];
            
            $update_query = "UPDATE tbl_nguoi_dung SET MatKhau=:MatKhau, TenNguoiDung = :TenNguoiDung, NgaySinh = :NgaySinh , STK=:STK
            WHERE idNguoiDung = :idNguoiDung ";
            
            $update_stmt = $conn->prepare($update_query);
            
            // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
            $update_stmt->bindValue(':MatKhau', htmlspecialchars(strip_tags($post_MatKhau)),PDO::PARAM_STR);
            $update_stmt->bindValue(':TenNguoiDung', htmlspecialchars(strip_tags($post_TenNguoiDung)),PDO::PARAM_STR);
            $update_stmt->bindValue(':NgaySinh', htmlspecialchars(strip_tags($post_NgaySinh)),PDO::PARAM_STR);
            $update_stmt->bindValue(':STK', $post_STK,PDO::PARAM_INT);
            $update_stmt->bindValue(':idNguoiDung', $post_id,PDO::PARAM_STR);
            // $update_stmt->bindValue(':SDT', $post_SDT,PDO::PARAM_INT);
            
            
            if($update_stmt->execute()){
                $msg['message'] = 'Success';
            }else{
                $msg['message'] = 'Faile';
            } 
        }
    }
    else{
        $msg['message'] = 'Khong ton tai';
    }  
echo  json_encode($msg); 
?>