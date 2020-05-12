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
if(isset($data->idHoatDong)){
    
    $msg['message'] = '';
    $post_idHoatDong = $data->idHoatDong;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM tblhoatdong WHERE idHoatDong=:idHoatDong";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':idHoatDong', $post_idHoatDong,PDO::PARAM_STR);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
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
        $data->sodu=$row['SoDuTK'];
        $post_update=$data->sodu+$post_moi;
        $data->SoNguoi=$row['SoNguoi'];
        $post_songuoi=$data->SoNguoi+1;
        $update_query = "UPDATE tblhoatdong SET SoDuTK = :SoDuTK, SoNguoi=:SoNguoi
         WHERE idHoatDong = :idHoatDong";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':SoDuTK', htmlspecialchars(strip_tags($post_update)),PDO::PARAM_INT);
        $update_stmt->bindValue(':idHoatDong', htmlspecialchars(strip_tags($post_idHoatDong)),PDO::PARAM_STR);
        $update_stmt->bindValue(':SoNguoi', htmlspecialchars(strip_tags($post_songuoi)),PDO::PARAM_STR);
        
        if($update_stmt->execute()){
            $msg['message'] = 'success';
        }else{
            $msg['message'] = 'Fail';
        }   
        
    }
    else{
        $msg['message'] = 'Hoat dong khong ton tai';
    }  
    
    echo  json_encode($msg);
    
}
?>