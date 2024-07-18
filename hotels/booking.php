<?php
require "../connection.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $location_id = $_POST['location_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $price_per_night = $_POST['price_per_night'];
    $one = 1;

    // 

    $stmt = $connection->prepare('INSERT INTO hotelbookings (user_id, hotel_id, check_in_date, check_out_date)
SELECT ?, h.id_hotel, ?, ?
FROM hotels h
WHERE h.location_id=? and h.price_per_night < ?;');

    $stmt->bind_param('issii',$one,$check_in_date,$check_out_date,$location_id,$price_per_night );

    try {
        $stmt->execute();
        echo json_encode(["message" => "flight was booked successfully", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
