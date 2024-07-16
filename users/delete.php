<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];

    $stmt = $connection->prepare('delete from users where username=?;');
    $stmt->bind_param('s', $username);
    try {
        $stmt->execute();
        echo json_encode(["message" => "user of username $username got deleted","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
