<?php
$servername="localhost";
$username="root";
$password="";
$db_name="flight management system";

$connection = new mysqli($servername,$username,$password,$db_name);

if($connection->connect_error){
    die("connection failed".$connection->connect_error);
}else{
    // echo"connection good";
}
