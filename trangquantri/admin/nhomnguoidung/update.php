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
if(isset($data->idNhom)){
    
    $msg['message'] = '';
    $post_id = $data->idNhom;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM tblnhomnguoidung WHERE idNhom=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $post_TenNhom = isset($data->TenNhom) ? $data->TenNhom : $row['TenNhom'];
        // $post_body = isset($data->body) ? $data->body : $row['body'];
        // $post_author = isset($data->author) ? $data->author : $row['author'];
        
        $update_query = "UPDATE tblnhomnguoidung SET TenNhom = :TenNhom
        WHERE idNhom = :id";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':TenNhom', htmlspecialchars(strip_tags($post_TenNhom)),PDO::PARAM_STR);
        $update_stmt->bindValue(':id', htmlspecialchars(strip_tags($post_id)),PDO::PARAM_STR);
        
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'success';
        }else{
            $msg['message'] = 'Faile';
        }   
        
    }
    else{
        $msg['message'] = 'ID khong ton tai';
    }  
    
    echo  json_encode($msg);
    
}
?>