<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $dep_country = $_GET["dep_country"];
    $arr_country = $_GET["arr_country"];
    $departure_time = $_GET["departure_time"];
    // 

    $stmt = $connection->prepare("SELECT f.*,a_dep.name as dep_airport,a_arr.name as arr_airport
FROM flights f, airports a_dep, locations l_dep, airports a_arr, locations l_arr
WHERE f.departure_airport_id = a_dep.id_airport
  AND a_dep.location_id = l_dep.id_location
  AND l_dep.country = ?
  AND f.arrival_airport_id = a_arr.id_airport
  AND a_arr.location_id = l_arr.id_location
  AND l_arr.country = ?
  AND f.departure_time > ?;");

    $stmt->bind_param('sss', $dep_country,$arr_country,$departure_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $flights = $result->fetch_assoc();
        echo json_encode(["flights" => $flights]);
    } else {
        echo json_encode(["message" => "no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
