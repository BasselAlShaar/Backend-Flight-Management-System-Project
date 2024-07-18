<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $flight_number = $_Get["flight_number"];

    $stmt = $connection->prepare('select * from flights where flight_number=?;');
    $stmt->bind_param('s', $flight_number);
    $stmt->execute();
    $result=$stmt->get_result();

    if ($result->num_rows>0){
        $flights=$result->fetch_assoc();
        echo json_encode(["flights"=>$flights]);
    } else {
        echo json_encode(["message"=>"no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
