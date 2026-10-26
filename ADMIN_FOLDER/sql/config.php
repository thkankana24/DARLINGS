<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = mysqli_connect(
    $hostname,
    $username,
    $password,
    $dbname
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>