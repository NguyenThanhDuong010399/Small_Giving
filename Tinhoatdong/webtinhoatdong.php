<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include_once '../database/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();
$data = json_decode(file_get_contents("php://input"));
if(isset($data->idHoatDong))
{
    $sql = "SELECT * FROM tblhoatdong where idHoatDong=:post"; 

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':post', $data->idHoatDong,PDO::PARAM_INT);
    $stmt->execute();

    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($stmt->rowCount() > 0){
        // CREATE POSTS ARRAY
        $posts_array = [];
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            $post_data = [
                'TenHoatDong' => $row['TenHoatDong'],
                'NoiDung' => $row['NoiDung'],
                //'body' => html_entity_decode($row['body']),
                'ThoiGianBD' => date("d-m-Y",strtotime($row['ThoiGianBD'])),
                'ThoiGianKT' => date("d-m-Y",strtotime($row['ThoiGianKT'])),
                'ChiDK' => $row['ChiDK'],
                'Anh' => $row['Anh']
            ];
            // PUSH POST DATA IN OUR $posts_array ARRAY
            array_push($posts_array, $post_data);
        }
        //SHOW POST/POSTS IN JSON FORMAT
        echo json_encode($posts_array);
    }
    else{
        //IF THER IS NO POST IN OUR DATABASE
        echo json_encode(['message'=>'Faile']);
    }
}
else
{
   echo json_encode(['message'=>'ID khong ton tai']); 
}
?>