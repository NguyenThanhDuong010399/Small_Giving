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
if(isset($data->idNguoiDung))
{
    $sql = "SELECT SoDuTK FROM tblnguoidung where idNguoiDung=:idNguoiDung"; 

$stmt = $conn->prepare($sql);
$stmt->bindValue(':idNguoiDung', $data->idNguoiDung,PDO::PARAM_INT);
$stmt->execute();

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
if($stmt->rowCount() > 0){
    // CREATE POSTS ARRAY
    $posts_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        $post_data = [
            'SoDuTK' => $row['SoDuTK'],
            // 'title' => $row['title'],
            // 'body' => html_entity_decode($row['body']),
            // 'author' => $row['author']
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
else{
     echo json_encode(['message'=>'Tai khoan khong ton tai']);
}

?>