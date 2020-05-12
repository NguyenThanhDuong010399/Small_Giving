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

// CHECK GET ID PARAMETER OR NOT

// MAKE SQL QUERY
// IF GET POSTS ID, THEN SHOW POSTS BY ID OTHERWISE SHOW ALL POSTS
$sql = "SELECT TenNguoiDung, SUM(SoTien) FROM tblquyengop JOIN tblnguoidung ON tblquyengop.idNguoiDung=tblnguoidung.idNguoiDung where 1=1 AND Month(tblquyengop.ThoiGian)=Month(CURDATE()) Group By TenNguoiDung ORDER BY Sum(SoTien) DESC"; 

$stmt = $conn->prepare($sql);

$stmt->execute();

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
if($stmt->rowCount() > 0){
    // CREATE POSTS ARRAY
    $posts_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        $post_data = [
            'TenNguoiDung' => $row['TenNguoiDung'],
            'SoTien' => $row['SoTien'],
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
    echo json_encode(['message'=>'No post found']);
}
?>