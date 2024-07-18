<?php
session_start();
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_SESSION['loggedin'])) {
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

    $stmt2 = $connection->prepare('select id_admin,email,password,username
                from admins where email=?');
    $stmt2->bind_param('s', $email);
    $stmt2->execute();
    $stmt2->store_result();
    $stmt2->bind_result($id_admin, $email, $pass, $username);
    $stmt2->fetch();
    $admin_exists = $stmt2->num_rows;
    if ($admin_exists != 0) {
        if ($password === $pass) {
            $_SESSION['isAdmin'] = true;
            $res['admin'] = $_SESSION['isAdmin'];
        }
    }

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
            echo json_encode($res);
        } else {
            $res['status'] = "wrong password";
        }
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
echo json_encode($res);
