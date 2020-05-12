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
$msg['message'] ='';
if(isset($data->idNguoiDung))
{
    // if(!emty($data->idNguoiDung))
    // {
        $sql = "SELECT * FROM tblnaptien WHERE idNguoiDung=:post_id"; 

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':post_id', $data->idNguoiDung,PDO::PARAM_INT);
        $stmt->execute();

        //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
        if($stmt->rowCount() > 0){
            // CREATE POSTS ARRAY
            $posts_array = [];
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                $post_data = [
                    'ThoiGian' => date("d-m-Y",strtotime($row['Thoigian'])),
                    'SoTien' => $row['SoTien'],
                    'NoiDung' => $row['NoiDung']
                    
                ];
                // PUSH POST DATA IN OUR $posts_array ARRAY
                array_push($posts_array, $post_data);
            }
            //SHOW POST/POSTS IN JSON FORMAT
            echo json_encode($posts_array);
         

        }
        else{
            //IF THER IS NO POST IN OUR DATABASE
            echo json_encode(['message'=>'Tai khoan khong ton tai']);
        }
    // }
    // else{
    //     $msg['message'] ='Khong duoc de trong idNguoiDung';
    // }
}
// MAKE SQL QUERY
// IF GET POSTS ID, THEN SHOW POSTS BY ID OTHERWISE SHOW ALL POSTS

?>