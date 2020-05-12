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
$msg['message'] ='';
//CREATE MESSAGE ARRAY AND SET EMPTY
if(isset($data->TenHoatDong))
{
    if(!empty($data->TenHoatDong))
    {
        $sql="SELECT idHoatDong from tblhoatdong where TenHoatDong=:TenHoatDong";
        $stmt=$conn->prepare($sql);
        $stmt->bindValue(':TenHoatDong', $data->TenHoatDong,PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            $data->idHoatDong=$row["idHoatDong"];
            if(isset($data->idNguoiDung)){
                // CHECK DATA VALUE IS EMPTY OR NOT
                if(!empty($data->idNguoiDung) ){
                    
                    $insert_query = "INSERT INTO tbltheodoi(idNguoiDung,idHoatDong) VALUES(:idNguoiDung,:idHoatDong)";
                    
                    $insert_stmt = $conn->prepare($insert_query);
                    // DATA BINDING
                    $insert_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($data->idNguoiDung)),PDO::PARAM_INT);
                    $insert_stmt->bindValue(':idHoatDong', htmlspecialchars(strip_tags($data->idHoatDong)),PDO::PARAM_INT);
                    // $insert_stmt->bindValue(':author', htmlspecialchars(strip_tags($data->author)),PDO::PARAM_STR);
                    
                    if($insert_stmt->execute()){
                        $msg['message'] = 'success';
                    }else{
                        $msg['message'] = 'Faile';
                    } 
                    
                }else{
                    $msg['message'] = 'Khong duoc de trong id nguoi dung';
                }
            }
            else{
                $msg['message'] = 'Tai khoan khong ton tai';
            }
        }
        else{
            $msg['message'] = 'Tai khoan khong ton tai';
        }
    }
}
else{
    $msg['message'] = 'Tai khoan khong ton tai';
}
// CHECK IF RECEIVED DATA FROM THE REQUEST

//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>