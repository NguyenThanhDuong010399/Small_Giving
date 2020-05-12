<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../../../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();
$data = json_decode(file_get_contents("php://input"));

// CHECK GET ID PARAMETER OR NOT
if($data->idTin!="")
{
    $post_id=$data->idTin;
}
else
{
    ['message'=>'khong duoc de id trong'];
    return;
}
// MAKE SQL QUERY
// IF GET POSTS ID, THEN SHOW POSTS BY ID OTHERWISE SHOW ALL POSTS
$sql = "SELECT * FROM tbl_tin_tuc where idTin='$post_id'";

$stmt = $conn->prepare($sql);
//$stmt->bindValue(':idNguoiDung', $data->idNguoiDung,PDO::PARAM_INT);
$stmt->execute();

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
if($stmt->rowCount() > 0){
    // CREATE POSTS ARRAY
    $posts_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        $post_data = [
            'idTin' => $row['idTin'],
            'idHoatDong' => $row['idHoatDong'],
            'TenTin' => $row['TenTin'],
            'NoiDung' => $row['NoiDung'],
            'ThoiGian' => date('d-m-Y',strtotime($row['ThoiGian'])),
            'Anh' => $row['Anh'],
            'TieuDeThongBao' => $row['TieuDeThongBao']
        ];
        // PUSH POST DATA IN OUR $posts_array ARRAY
        array_push($posts_array, $post_data);
    }
    //SHOW POST/POSTS IN JSON FORMAT
    echo json_encode($posts_array);
 

}
else{
    //IF THER IS NO POST IN OUR DATABASE
    echo json_encode(['message'=>'Fail']);
}
?>