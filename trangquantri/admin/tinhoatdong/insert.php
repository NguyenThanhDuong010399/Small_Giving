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
if(isset($data->idTin))
{
    if($data->idTin!="")
    {
        $sql="SELECT * from tbl_tin_tuc where idTin=:idTin";
        $stmt=$conn->prepare($sql);
        if($stmt->rowCount()>0)
        {
            $msg['message'] = 'Tai khoan da ton tai';
        }
        else{
          $insert_query = "INSERT INTO tbl_tin_tuc(TenTin,NoiDung,Anh,idHoatDong,TieuDeThongBao) VALUES(:TenTin,:NoiDung,:Anh,:idHoatDong,:TieuDeThongBao)";
    
            $insert_stmt = $conn->prepare($insert_query);
            // DATA BINDING
            $insert_stmt->bindValue(':TenTin', htmlspecialchars(strip_tags($data->TenTin)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':NoiDung', htmlspecialchars(strip_tags($data->NoiDung)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':Anh', htmlspecialchars(strip_tags($data->Anh)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':idHoatDong', htmlspecialchars(strip_tags($data->idHoatDong)),PDO::PARAM_STR);
            $insert_stmt->bindValue(':TieuDeThongBao', htmlspecialchars(strip_tags($data->TieuDiemThongBao)),PDO::PARAM_STR);
            if($insert_stmt->execute()){
                $msg['message'] = 'success';
            }else{
                $msg['message'] = 'Faile';
            } 
        }
    }
}
else{
    $msg['message'] = 'ID khong ton tai';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>