<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $location_id = $_POST["location_id"];
    $availability_status = $_POST["availability_status"] ?? null;
    $price_per_night = $_POST["price_per_night"];
    $rating = $_POST["rating"];

    $stmt = $connection->prepare('insert into hotels (name,location_id,availability_status,price_per_night,rating) 
values (?,?,?,?,?);');
    $stmt->bind_param('sisid', $name, $location_id, $availability_status, $price_per_night, $rating);
    try {
        $stmt->execute();
        echo json_encode(["message" => "new hotel created", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
