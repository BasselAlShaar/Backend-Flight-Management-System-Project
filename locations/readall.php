<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $stmt = $connection->prepare('select * from locations;');
    $stmt->execute();
    $result=$stmt->get_result();

    if ($result->num_rows>0){
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row;
        }
        echo json_encode(["locations"=>$locations]);
    } else {
        echo json_encode(["message"=>"no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
