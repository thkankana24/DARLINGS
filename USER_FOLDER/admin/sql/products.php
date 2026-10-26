<?php
require_once __DIR__ . '/../../sql/config.php';

if(isset($_POST['okk'])){

    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $product_size = $_POST['product_size'];
    $discount = $_POST['discount'];
    $product_status = $_POST['product_status'];
    $product_description = $_POST['product_description'];
    $image = $_FILES['image']['name'];

    move_uploaded_file($_FILES['image']['tmp_name'], '../upload/' .$_FILES['image']['name']);


    $query = "INSERT INTO products(productname,category,price,product_size,discount,product_status,description,image)
    VALUES(?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn, $query);

    if(!$stmt){
        die("Prepare Failed :" .mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssdsdsss", $product_name,$category, $price,$product_size,$discount,
    $product_status, $product_description,$image);

    if(mysqli_stmt_execute($stmt)){
        echo "<script>
                alert('Product Added Successfully');
                window.location='../product.php';
        </script>";
    }
    else{
        echo "Error" .mysqli_stmt_error(($stmt));
    }
    mysqli_stmt_close($stmt);    

}

mysqli_close($conn);


?>
