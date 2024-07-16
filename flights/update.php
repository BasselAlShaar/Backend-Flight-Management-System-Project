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

    if (empty($id_flight) || empty($flight_number) || empty($departure_airport_id) || empty($arrival_airport_id) || empty($departure_time) || empty($arrival_time) || empty($status) || empty($price) || empty($total_seats) || empty($airline_id)) {
        echo json_encode(["error" => "All fields are required"]);
        exit();
    }

    if (!is_numeric($id_flight) || !is_numeric($departure_airport_id) || !is_numeric($arrival_airport_id) || !is_numeric($price) || !is_numeric($total_seats) || !is_numeric($airline_id)) {
        echo json_encode(["error" => "ID fields, price, and total seats must be numeric"]);
        exit();
    }

    // Validate datetime format (Y-m-d H:i:s)
    $date_format = 'Y-m-d H:i:s';
    $d = DateTime::createFromFormat($date_format, $departure_time);
    if (!$d || $d->format($date_format) != $departure_time) {
        echo json_encode(["error" => "Invalid departure time format"]);
        exit();
    }
    $d = DateTime::createFromFormat($date_format, $arrival_time);
    if (!$d || $d->format($date_format) != $arrival_time) {
        echo json_encode(["error" => "Invalid arrival time format"]);
        exit();
    }

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
