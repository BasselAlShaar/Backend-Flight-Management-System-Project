<?php
require "../connection.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $one = 1;

    $data = file_get_contents("php://input");
    $params = json_decode($data,true);


    $location_id = $params['location_id'];
    $check_in_date = $params['check_in_date'];
    $check_out_date = $params['check_out_date'];
    $price_per_night = $params['price_per_night'];
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
