<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_flight = $_POST["id_flight"];
    $flight_number = $_POST["flight_number"];
    $departure_airport_id = $_POST["departure_airport_id"];
    $arrival_airport_id = $_POST["arrival_airport_id"];
    $departure_time = $_POST["departure_time"];
    $arrival_time = $_POST["arrival_time"];
    $status = $_POST["status"];
    $price = $_POST["price"];
    $total_seats = $_POST["total_seats"];
    $airline_id = $_POST["airline_id"];

    // Prepare the SQL statement for update
    $stmt = $connection->prepare('UPDATE flights SET flight_number = ?, departure_airport_id = ?, arrival_airport_id = ?, departure_time = ?, arrival_time = ?, status = ?, price = ?, total_seats = ?, airline_id = ? WHERE id_flight = ?');
    $stmt->bind_param('siisssiiii', $flight_number, $departure_airport_id, $arrival_airport_id, $departure_time, $arrival_time, $status, $price, $total_seats, $airline_id, $id_flight);

    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Flight updated successfully", "status" => "success"]);
        } else {
            echo json_encode(["error" => "No rows updated"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>
