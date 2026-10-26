<?php

require_once __DIR__ . '/../../sql/config.php';

$id = $_GET['id'];
$query = "DELETE FROM products WHERE id = '$id'";

if(mysqli_query($conn, $query)){
    echo "<script>
            alert('Product Deleted');
            window.location = '../product.php';
    </script>";
}else{
    echo "error" .mysqli_error($conn);
}

?>
