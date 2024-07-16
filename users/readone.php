<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];

    $stmt = $connection->prepare('select * from users where username=?;');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result=$stmt->get_result();

    if ($result->num_rows>0){
        $user=$result->fetch_assoc();
        echo json_encode(["user"=>$user]);
    } else {
        echo json_encode(["message"=>"no records were found"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
