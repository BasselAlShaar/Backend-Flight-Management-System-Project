<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $location_id = $_POST["location_id"];

    $stmt = $connection->prepare('select * from hotels where location_id=?;');
    $stmt->bind_param('i', $location_id);
    $stmt->execute();
    $result=$stmt->get_result();

    if ($result->num_rows>0){
        $hotels=$result->fetch_assoc();
        echo json_encode(["hotels"=>$user]);
    } else {
        echo json_encode(["message"=>"no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
