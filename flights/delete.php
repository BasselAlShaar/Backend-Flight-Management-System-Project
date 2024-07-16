<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_flight = $_POST["id_flight"];

    // Check if flight ID is provided and is numeric
    if (empty($id_flight) || !is_numeric($id_flight)) {
        echo json_encode(["error" => "Flight ID is required and must be numeric"]);
        exit();
    }

    // Prepare the SQL statement for deletion
    $stmt = $connection->prepare('DELETE FROM flights WHERE id_flight = ?');
    $stmt->bind_param('i', $id_flight);

    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Flight deleted successfully", "status" => "success"]);
        } else {
            echo json_encode(["error" => "No flight found with the provided ID"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}

