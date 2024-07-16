<?php
session_start();
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connection->prepare('select id_user,email,password,username
    from users 
    where email=?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    // this will make you access $id, $email, $hashed_password, $name
    $stmt->bind_result($id_user, $email, $hashed_password, $username);
    $stmt->fetch();
    $user_exists = $stmt->num_rows;

    if ($user_exists == 0) {
        $res['message'] = "user not found";
    } else {
        if (password_verify($password, $hashed_password)) {
            $res['status'] = 'authenticated';
            $res['id_user'] = $id_user;
            $res['username'] = $username;
            $res['email'] = $email;

            $_SESSION['id_user'] = $id_user;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
        } else {
            $res['status'] = "wrong password";
        }
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
echo json_encode($res);
