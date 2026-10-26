<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ecommerce';

$conn = mysqli_connect($hostname,$username,$password,$dbname);

if(!$conn){
    die("Failed Connection" .mysqli_connect_error());
}

?>