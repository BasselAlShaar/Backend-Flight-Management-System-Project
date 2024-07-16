<?php

require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $check_email = $connection->prepare('select * from users where username=? or email=?;');
    $check_email->bind_param('ss', $username, $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Already exists"]);
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["error" => "Invalid email format"]);
            exit();
        }
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $connection->prepare('insert into users(username,email,password,first_name,last_name) values (?,?,?,?,?); ');
        $stmt->bind_param('sssss', $username, $email, $hashed_password,$first_name,$last_name);
        $stmt->execute();
        $res['status'] = "success";
        $res['message'] = "inserted successfully";

        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true;

        echo json_encode($res);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
