<?php

require_once __DIR__ . '/../../sql/config.php';

$id = $_GET['id'];
$query = "DELETE FROM customers WHERE id = '$id'";

if(mysqli_query($conn, $query)){
    echo "<script>
            alert('Customer Details Deleted');
            window.location = '../customer.php';
    </script>";
}else{
    echo "error" .mysqli_error($conn);
}

?>
