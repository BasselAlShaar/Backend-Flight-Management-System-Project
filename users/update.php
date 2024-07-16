<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $connection->prepare('update users set password=? where username=?;');
    $stmt->bind_param('ss', $usernam,$password);
    try {
        $stmt->execute();
        echo json_encode(["message" => "user of username $username got updated","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
