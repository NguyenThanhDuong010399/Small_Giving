<?php 
// required headers
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: access");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Credentials: true");
// header('Content-Type: application/json');
 
// // include database and object files
// include_once '../database/database.php';
// include_once '../index.php';
 
// // get database connection
// $database = new Database();
// $db = $database->getConnection();
 
// // prepare product object
// $dangky = new Dangky($db);
 
// // set ID property of record to read
// $dangky->SDT = isset($_GET['SDT']) ? $_GET['SDT'] : die();
 
// // read the details of product to be edited
// $dangky->kiemtra();
 
// if($dangky->SDT!=null){
//     // create array
//     // $product_arr = array(
//     //     "SDT" =>  $dangky->SDT,
//     //     // "Email" => $dangky->Email,
//     //     //"NgayNhan" => $product->NgayNhan
//     // );
 
//     // // set response code - 200 OK
//     // http_response_code(200);
 
//     // // make it json format
//     // echo json_encode($product_arr);
//     echo json_encode(array("message" => "true."));
// }
// else{
//     // set response code - 404 Not found
//     http_response_code(404);
 
//     // tell the user product does not exist
//     // echo json_encode(array("message" => "Product does not exist."));
//     echo json_encode(array("message" => "false."));
// }
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
include_once '../jwt.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

// GET DATA FORM REQUEST
//$data = json_decode(file_get_contents("php://input"));
$data=file_get_contents("php://input");
$obj=json_decode($data,true);
$Email=$obj['Email'];
$SDT=$obj['SDT'];
$TenNguoiDung=$obj['TenNguoiDung'];
$MatKhau=md5($obj['MatKhau']);
$nhahaotam="4";
if($Email!=''&&$SDT!='')
{
    $sql="SELECT TenNguoiDung from tblnguoidung where Email='$Email' or SDT='$SDT'";
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount()>0)
    {
        echo json_encode(['message'=>'Tai khoan da ton tai']);
        //bị trùng
    }
    else
    {
        //echo json_encode(['message'=>'403']);
        if($TenNguoiDung!=''&&$MatKhau!='')
        {
            $sql1="INSERT INTO tblnguoidung(Email,SDT,TenNguoiDung,MatKhau,idNhom) VALUES('$Email','$SDT','$TenNguoiDung','$MatKhau','$nhahaotam')";
            $stmt=$conn->prepare($sql1);
            if($stmt->execute())
            {
                echo json_encode(['message'=>'Dang ki thanh cong']);
            }
            else
            {
                echo json_encode(['message'=>'Loi dang ki']);
                //Lỗi đăng kí
            }
        }
        else
        {
            echo json_encode(['message'=>'Khong duoc de trong ten hoac mat khau']);
            //để trong tên hoặc mật khẩu
        }
    }
}
else
{
    echo json_encode(['message'=>'khong duoc de trong Email hoac SDT']);
    //nhập thiếu email hoặc sdt
}
//ECHO DATA IN JSON FORMAT
?>