<?php 
   
// // SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();
$data = json_decode(file_get_contents("php://input"));
// CHECK GET ID PARAMETER OR NOT
$sql="";
$msg['message'] ='';
if(isset($data->Email)&&isset($data->SDT))
{
    $post_Email=$data->Email;
    $post_SDT=$data->SDT;
    if($post_Email!="" && $post_SDT!="")
    {
        $sql="SELECT MatKhau from tblnguoidung where Email=:post_Email AND SDT=:post_SDT";
        $stmt=$conn->prepare($sql);
        $stmt->bindValue(':post_SDT', $post_SDT,PDO::PARAM_INT);
        $stmt->bindValue(':post_Email', $post_Email,PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            $post_array=[];
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                $post_data=[
                    'MatKhau'=>$row['MatKhau']
                ];
                array_push($post_array, $post_data);
            }
            echo json_encode($post_array);
        }
        else
        {
            echo json_encode(['message'=>'Tai khoan khong ton tai']);
        }
    }
    else
    {
        echo json_encode(['message'=>'Khong duoc de trong Email va SDT']);
    }
}
else
{
    echo json_encode(['message'=>'ERROR']);
}
?>