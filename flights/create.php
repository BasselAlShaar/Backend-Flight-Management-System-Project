<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $flight_number = $_POST["flight_number"];
    $departure_airport_id = $_POST["departure_airport_id"];
    $arrival_airport_id = $_POST["arrival_airport_id"];
    $departure_time = $_POST["departure_time"];
    $arrival_time = $_POST["arrival_time"];
    $status = $_POST["status"];
    $price = $_POST["price"];
    $total_seats = $_POST["total_seats"];
    $airline_id = $_POST["airline_id"];

    $stmt = $connection->prepare('INSERT INTO flights (flight_number, departure_airport_id, arrival_airport_id, departure_time, arrival_time, 
    status, price, total_seats, airline_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);');
    $stmt->bind_param('siisssiii', $flight_number, $departure_airport_id, $arrival_airport_id, $departure_time, $arrival_time, $status, $price, $total_seats, $airline_id);

    try {
        $stmt->execute();
        echo json_encode(["message" => "New flight created", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
