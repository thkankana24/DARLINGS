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
<?php

$id = $_GET['id'];

$query = "DELETE FROM products_admin WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('product deleted');
        window.location='../product.php';
    </script>";
} else {
    echo "error: " . mysqli_error($conn);
}
?>
