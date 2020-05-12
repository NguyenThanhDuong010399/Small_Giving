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
if(isset($data->idHoatDong)&&isset($data->idNguoiDung))
{
    $post_idHD=$data->idHoatDong;
    $post_idND=$data->idNguoiDung;
    $sql="SELECT * from tblhoatdong where idHoatDong=:post_idHD";
    $stmt=$conn->prepare($sql);
    $stmt->bindValue(':post_idHD', $post_idHD,PDO::PARAM_INT);
    $sql1="SELECT * from tblnguoidung where idNguoiDung=:post_idND";
    $stmt1=$conn->prepare($sql1);
    $stmt1->bindValue(':post_idND', $post_idND,PDO::PARAM_INT);

    $stmt->execute();
    $stmt1->execute();
    if($stmt->rowCount()>0&&$stmt1->rowCount()>0)
    {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $row1=$stmt1->fetch(PDO::FETCH_ASSOC);
        $post_TenHD=$row['TenHoatDong'];
        $data->sodu=$row['SoDuTK'];
        $data->songuoi=$row['SoNguoi'];
        $data->sodu1=$row1['SoDuTK'];

        if(isset($data->idNguoiDung) && isset($data->SoTien)){
            // CHECK DATA VALUE IS EMPTY OR NOT
            if($data->idNguoiDung!=""&&$data->idHoatDong!=""&&$data->SoTien!=""){
                //gán số tiền
                if(isset($data->SoTien))
                {
                    if($data->SoTien>0)
                    {
                        $post_moi=$data->SoTien;
                    }
                    else
                    {
                        $post_moi=0;
                    }
                }
                //Số dư mới của tblhoatdong
                $post_UdSoDu=$post_moi+$data->sodu;
                $post_UdSoDu1=$data->sodu1-$post_moi;
                //
                //--update số người của tblhoatdong
                $post_UdSoNguoi=$data->songuoi+1;
                // echo $post_UdSoDu1 ;
                // echo  $post_UdSoDu;
                // echo  $post_UdSoNguoi;
                // return;
                //---SQL
                $insert_query = "INSERT INTO tblquyengop(idNguoiDung,idHoatDong,SoTien) VALUES(:idNguoiDung,:idHoatDong,:SoTien)";
                
                
                 
                 //
                $insert_stmt = $conn->prepare($insert_query);
                
                
                
                // DATA insert tblquyengop
                $insert_stmt->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($data->idNguoiDung)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':idHoatDong', htmlspecialchars(strip_tags($data->idHoatDong)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':SoTien', htmlspecialchars(strip_tags($data->SoTien)),PDO::PARAM_STR);
                //DATA insert tbllichsugiaodich
                $insert="INSERT INTO tbllichsugiaodich(idNguoiDung,idHoatDong,TenHoatDong,SoTienQG) VALUES(:idNguoiDung,:idHoatDong,:TenHoatDong,:SoTien)";
                $insert_stmt1 = $conn->prepare($insert);
                $insert_stmt1->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($data->idNguoiDung)),PDO::PARAM_STR);
                $insert_stmt1->bindValue(':idHoatDong', htmlspecialchars(strip_tags($data->idHoatDong)),PDO::PARAM_STR);
                $insert_stmt1->bindValue(':TenHoatDong', htmlspecialchars(strip_tags($post_TenHD)),PDO::PARAM_STR);
                $insert_stmt1->bindValue(':SoTien', htmlspecialchars(strip_tags($data->SoTien)),PDO::PARAM_STR);
                //DATA update tblhoatdong
                $update_HD="UPDATE tblhoatdong SET SoDuTK = :SoDuTK, SoNguoi=:SoNguoi
                 WHERE idHoatDong = :idHoatDong";
                 $update_stmt=$conn->prepare($update_HD);
                $update_stmt->bindValue(':SoDuTK', htmlspecialchars(strip_tags($post_UdSoDu)),PDO::PARAM_INT);
                $update_stmt->bindValue(':idHoatDong', htmlspecialchars(strip_tags($post_idHD)),PDO::PARAM_STR);
                $update_stmt->bindValue(':SoNguoi', htmlspecialchars(strip_tags($post_UdSoNguoi)),PDO::PARAM_STR);
                //DATA update tblnguoidung
                $update_ND="UPDATE tblnguoidung SET SoDuTK = :SoDuTK1
                 WHERE idNguoiDung = :idNguoiDung";
                 $update_stmt1=$conn->prepare($update_ND);
                $update_stmt1->bindValue(':SoDuTK1', htmlspecialchars(strip_tags($post_UdSoDu1)),PDO::PARAM_INT);
                $update_stmt1->bindValue(':idNguoiDung', htmlspecialchars(strip_tags($post_idND)),PDO::PARAM_STR);
                if($insert_stmt->execute()&&$insert_stmt1->execute()&&$update_stmt->execute()&&$update_stmt1->execute()){
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