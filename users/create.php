<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST['last_name'];

    // email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Invalid email format"]);
        exit();
    }

    // INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `first_name`, `last_name`) VALUES (NULL, 'test2', '1234', '123@gmail.com', 'asdf', 'asdf'); 
    $stmt = $connection->prepare('insert into users (username,password,email,first_name,last_name) 
values (?,?,?,?,?);');
    $stmt->bind_param('sssss', $username, $password, $email, $first_name, $last_name);
    try {
        $stmt->execute();
        echo json_encode(["message" => "new user created", "status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
