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

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';
if(isset($data->idHoatDong))
{
    $post_idHD=$data->idHoatDong;
    $sql="SELECT * from tblhoatdong where idHoatDong=:post_idHD";
    $stmt=$conn->prepare($sql);
    $stmt->bindValue(':post_idHD', $post_idHD,PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount()>0)
    {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $post_TenHD=$row['TenHoatDong'];
        if(isset($data->idNguoiDung) && isset($data->SoTien)){
            // CHECK DATA VALUE IS EMPTY OR NOT
            if($data->idNguoiDung!=""&&$data->idHoatDong!=""&&$data->SoTien){
                
                $insert_query = "INSERT INTO tblquyengop(idNguoiDung,idHoatDong,SoTien) VALUES(:idNguoiDung,:idHoatDong,:SoTien)";
                $insert="INSERT INTO tbllichsugiaodich(idNguoiDung,idHoatDong,TenHoatDong,SoTienQG) VALUES(:idNguoiDung,:idHoatDong,:TenHoatDong,:SoTien)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt1 = $conn->prepare($insert);
                // DATA BINDING
                $insert_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($data->idNguoiDung)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':idHoatDong', htmlspecialchars(strip_tags($data->idHoatDong)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':SoTien', htmlspecialchars(strip_tags($data->SoTien)),PDO::PARAM_STR);

                $insert_stmt1->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($data->idNguoiDung)),PDO::PARAM_STR);
                $insert_stmt1->bindValue(':idHoatDong', htmlspecialchars(strip_tags($data->idHoatDong)),PDO::PARAM_STR);
                $insert_stmt1->bindValue(':TenHoatDong', htmlspecialchars(strip_tags($post_TenHD)),PDO::PARAM_STR);
                $insert_stmt1->bindValue(':SoTien', htmlspecialchars(strip_tags($data->SoTien)),PDO::PARAM_STR);
                
                if($insert_stmt->execute()&&$insert_stmt1->execute()){
                    $msg['message'] = 'success';
                }else{
                    $msg['message'] = 'Fail';
                } 
                
            }else{
                $msg['message'] = 'khong duoc de mot trong cac truong trong';
            }
        }
        else{
            $msg['message'] = 'khong ton tai';
        }
    }
    else
    {
        $msg['message'] = 'hoat dong khong ton tai';
    }

}
// CHECK IF RECEIVED DATA FROM THE REQUEST

//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>