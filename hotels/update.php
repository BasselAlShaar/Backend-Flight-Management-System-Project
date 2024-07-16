<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_hotel = $_POST["id_hotel"];
    $price_per_night = $_POST["price_per_night"];

    $stmt = $connection->prepare('update hotels set price_per_night=? where id_hotel=?;');
    $stmt->bind_param('ii', $price_per_night,$id_hotel);
    try {
        $stmt->execute();
        echo json_encode(["message" => "hotel of id $id_hotel got updated","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
