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
if(isset($data->SDT)){
    
    $msg['message'] = '';
    $post_id = $data->SDT;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT idNguoiDung,SoDuTK FROM tblnguoidung WHERE SDT=:post_SDT";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_SDT', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        $post_id1=$row["idNguoiDung"];
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        //$data->SoDuTK = $row['SoDuTK'];
        //$post_SDTK=$data->SoDuTK;
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
        //$post_moi = isset($data->moi) ? $data->moi : 0;
        $data->SoDu = $row['SoDuTK'];
        // $post_author = isset($data->author) ? $data->author : $row['author'];
        $post_SDTK=$post_moi+$data->SoDu;
        $update_query = "UPDATE tblnguoidung SET SoDuTK = :post_SDTK
        WHERE SDT = :SDT";
        $insert = "INSERT INTO tbllichsugiaodich(idNguoiDung,SoTienNap) VALUES(:idNguoiDung,:SoTienNap)";
        
        $update_stmt = $conn->prepare($update_query);
        $insert_stmt = $conn->prepare($insert);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        // $update_stmt->bindValue(':SoDu', htmlspecialchars(strip_tags($post_SoDu->SoDu)),PDO::PARAM_INT);
        $update_stmt->bindValue(':post_SDTK', htmlspecialchars(strip_tags($post_SDTK)),PDO::PARAM_INT);
        // $update_stmt->bindValue(':author', htmlspecialchars(strip_tags($post_author)),PDO::PARAM_STR);
        $update_stmt->bindValue(':SDT', $post_id,PDO::PARAM_INT);

        $insert_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($post_id1)),PDO::PARAM_INT);
        // $update_stmt->bindValue(':author', htmlspecialchars(strip_tags($post_author)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':SoTienNap', $post_moi,PDO::PARAM_INT);
        
        if($update_stmt->execute()&&$insert_stmt->execute()){
            $msg['message'] = 'success';
        }else{
            $msg['message'] = 'Faile';
        }   
        
    }
    else{
        $msg['message'] = 'Tai khoan khong ton tai';
    }  
}
else{
        $msg['message'] = 'Khong duoc de trong sdt';
    }
    echo  json_encode($msg);
?>