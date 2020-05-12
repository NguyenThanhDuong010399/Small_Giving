<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->idNguoiDung)){
    
    $msg['message'] = '';
    $post_Email = $data->idNguoiDung;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT SoDuTK FROM tblnguoidung WHERE idNguoiDung=:idNguoiDung";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':idNguoiDung', $post_Email,PDO::PARAM_STR);
    $get_stmt->execute();
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($data->moi))
        {
            if($data->moi>0)
            {
                $post_moi=$data->moi;
            }
            else
            {
                $post_moi=0;
            }
        }
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $data->SoDu=$row['SoDuTK'];
        $post_update=$data->SoDu-$post_moi;
        
        $update_query = "UPDATE tblnguoidung SET SoDuTK = :post_update
        WHERE idNguoiDung = :idNguoiDung";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':post_update', htmlspecialchars(strip_tags($post_update)),PDO::PARAM_INT);
        $update_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($post_Email)),PDO::PARAM_STR);
        // $update_stmt->bindValue(':author', htmlspecialchars(strip_tags($post_author)),PDO::PARAM_STR);
        // $update_stmt->bindValue(':id', $post_id,PDO::PARAM_INT);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'success';
        }else{
            $msg['message'] = 'Faile';
        }   
        
    }
    else{
        $msg['message'] = 'Tai khoan khong ton tai';
    }  
    
    echo  json_encode($msg);
    
}
?>