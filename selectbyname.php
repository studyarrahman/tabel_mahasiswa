<?php
include 'connection.php';
// selectbyname.php
$conn = getConnection();

$id = $_GET["nama"];

try {
    $statement = $conn->prepare("SELECT * FROM mahasiswa WHERE nama = :nama;");
    $statement->bindParam(':nama', $id);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_OBJ);
    $result = $statement->fetch();

    if($result){
        echo json_encode($result, JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        $response["message"] = "informasi mahasiswa tidak ditemukan";
        echo json_encode($response,JSON_PRETTY_PRINT);
    }

} catch (PDOException $e) {
    echo $e;
}