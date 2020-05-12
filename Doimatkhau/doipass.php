<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
$database = new Database();
$conn = $database->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->idNguoiDung)&&isset($data->NewPass)&&isset($data->MatKhau)){
    
    $msg['message'] = '';
    
    $post_idNguoiDung = $data->idNguoiDung;
    $pass_cu=$data->MatKhau;
    $pass_moi=$data->NewPass;
    // $post_SDT = $data->SDT;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM tblnguoidung WHERE idNguoiDung=:idNguoiDung AND MatKhau=:post_cu";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':idNguoiDung', $post_idNguoiDung,PDO::PARAM_INT);
    $get_stmt->bindValue(':post_cu', $pass_cu,PDO::PARAM_STR);
    // $get_stmt->bindValue(':SDT', $post_SDT,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $post_MatKhau = isset($data->NewPass) ? $data->NewPass : $row['MatKhau'];
        // $post_body = isset($data->body) ? $data->body : $row['body'];
        // $post_author = isset($data->author) ? $data->author : $row['author'];
        
        $update_query = "UPDATE tblnguoidung SET MatKhau = :MatKhau 
        WHERE idNguoiDung = :idNguoiDung";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':MatKhau', htmlspecialchars(strip_tags($pass_moi)),PDO::PARAM_STR);
        $update_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($post_idNguoiDung)),PDO::PARAM_STR);
        // $update_stmt->bindValue(':author', htmlspecialchars(strip_tags($post_author)),PDO::PARAM_STR);
        // $update_stmt->bindValue(':Email', $post_Email,PDO::PARAM_STR);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'Success';
        }else{
            $msg['message'] = 'Faile';
        }   
        
    }
    else{
        $msg['message'] = 'Khong ton tai';
    }  
    
    echo  json_encode($msg);
    
}
?>