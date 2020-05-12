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
if(isset($data->TenNhom))
{
    $post_ten=$data->TenNhom;
    if($post_ten!="")
    {
        $sql="SELECT * from tblnhomnguoidung where TenNhom=:TenNhom";
        $get_stmt=$conn->prepare($sql);
        $get_stmt->bindValue(':TenNhom', htmlspecialchars(strip_tags($post_ten)),PDO::PARAM_STR);
        $get_stmt->execute();
        if($get_stmt->rowCount()>0)
        {
            $msg['message'] = 'Tai khoan da ton tai';
        }
        else{
            if(isset($data->TenNhom))
            {
                $insert_query = "INSERT INTO tblnhomnguoidung(TenNhom) VALUES(:TenNhom)";
                $insert_stmt = $conn->prepare($insert_query);
                // DATA BINDING
                // $insert_stmt->bindValue(':idNhom', htmlspecialchars(strip_tags($data->idNhom)),PDO::PARAM_INT);
                $insert_stmt->bindValue(':TenNhom', htmlspecialchars(strip_tags($data->TenNhom)),PDO::PARAM_STR);
                if($insert_stmt->execute()){
                    $msg['message'] = 'success';
                }else{
                    $msg['message'] = 'Faile';
                } 
            }
            else{
                $msg['message'] = 'Id khong ton tai';
            }
        }
    }
    else
    {
        $msg['message'] = 'Id k';
    }
}
else{
    $msg['message'] = 'ID khong ton tai';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>