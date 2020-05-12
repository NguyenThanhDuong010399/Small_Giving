<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
include_once '../jwt.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();
$data = json_decode(file_get_contents("php://input"));
// CHECK GET ID PARAMETER OR NOT
$sql="";
$msg['message'] ='';

if((isset($data->Email)&&isset($data->MatKhau))||(isset($data->SDT)&&isset($data->MatKhau))){
    //IF HAS ID PARAMETER
    if(isset($data->Email)&&(isset($data->MatKhau)))
    {
    	$post_Email = $data->Email;
    	$post_MatKhau = $data->MatKhau;
    	$sql = "SELECT idNguoiDung,TenNguoiDung,Email,MatKhau FROM tblnguoidung WHERE Email=:post_Email AND MatKhau=:post_MatKhau AND (idNhom='1' or idNhom='2' or idNhom='3')"  ; 
    	$stmt = $conn->prepare($sql);
    	$stmt->bindValue(':post_Email', $post_Email,PDO::PARAM_STR);
    	$stmt->bindValue(':post_MatKhau', $post_MatKhau,PDO::PARAM_STR);
    }
    else if(isset($data->SDT)&&(isset($data->MatKhau))){
    	$post_SDT = $data->SDT;
    	$post_MatKhau = $data->MatKhau;
    	$sql = "SELECT idNguoiDung,TenNguoiDung,SDT,MatKhau FROM tblnguoidung WHERE SDT=:post_SDT AND MatKhau=:post_MatKhau AND (idNhom='1' or idNhom='2' or idNhom='3')" ;
    	$stmt = $conn->prepare($sql);
		$stmt->bindValue(':post_SDT', $post_SDT,PDO::PARAM_INT);
		$stmt->bindValue(':post_MatKhau', $post_MatKhau,PDO::PARAM_STR);
    }
    else{
    	$msg['message'] = 'error';
    }
	$stmt->execute();

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
	if($stmt->rowCount() > 0){
	    $row=$stmt->fetch(PDO::FETCH_ASSOC);
	    $data->idNguoiDung=$row["idNguoiDung"];
	    $data->TenNguoiDung=$row["TenNguoiDung"];
	    $token=array();
	    $token["idNguoiDung"]=$data->idNguoiDung;
	    if(isset($data->Email))
	    {
	    	$token["Email"]=$data->Email;
	    }
	    else
	    {
	    	$token["SDT"]=$data->SDT;
	    }
	    $token["TenNguoiDung"]=$data->TenNguoiDung;

	    $jsonToken=JWT::encode($token,"Small_Giving");

	 	echo JsonHelper::getJson("token",$jsonToken);
	 	//sinh ra token
	}
	else{
	    //IF THER IS NO POST IN OUR DATABASE
	    $token=array();
	    echo json_encode(['token'=>'Error']);
	}
} 
 ?>