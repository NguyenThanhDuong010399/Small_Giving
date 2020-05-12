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
if(isset($data->Email)||isset($data->SDT)){
    
    $msg['message'] = '';
    $post_Email = $data->Email;
    $post_SDT = $data->SDT;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM tblnguoidung WHERE Email=:Email or SDT=:SDT";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':Email', $post_Email,PDO::PARAM_STR);
    $get_stmt->bindValue(':SDT', $post_SDT,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $post_MatKhau = isset($data->MatKhau) ? $data->MatKhau : $row['MatKhau'];
        // $post_body = isset($data->body) ? $data->body : $row['body'];
        // $post_author = isset($data->author) ? $data->author : $row['author'];
        
        $update_query = "UPDATE tblnguoidung SET MatKhau = :MatKhau 
        WHERE Email = :Email OR SDT=:SDT";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':MatKhau', htmlspecialchars(strip_tags($post_MatKhau)),PDO::PARAM_STR);
        $update_stmt->bindValue(':SDT', htmlspecialchars(strip_tags($post_SDT)),PDO::PARAM_STR);
        // $update_stmt->bindValue(':author', htmlspecialchars(strip_tags($post_author)),PDO::PARAM_STR);
        $update_stmt->bindValue(':Email', $post_Email,PDO::PARAM_STR);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'Data updated successfully';
        }else{
            $msg['message'] = 'data not updated';
        }   
        
    }
    else{
        $msg['message'] = 'Invlid ID';
    }  
    
    echo  json_encode($msg);
    
}
?>