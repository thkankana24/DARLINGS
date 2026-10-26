<?php

$hostname="localhost";
$username="root";
$password="";
$dbname="ecommerce";

$conn=mysqli_connect($hostname,$username,$password,$dbname);

if(!$conn){
    die("Failed Connection".mysqli_connect_error());
}
if(isset($_POST['done'])){
    $product_name=$_POST['product_name'];
    $category=$_POST['category'];
    $price=$_POST['price'];
    $stock=$_POST['stock'];
    $size=$_POST['size'];
    $discount=$_POST['discount'];
    $description=$_POST['description'];
    $product_image=$_FILES['product_image']['name'];
    
    move_uploaded_file($_FILES['product_image']['tmp_name'],'../upload/' .$_FILES['product_image']['name']);

    $query="INSERT INTO products_admin(product_name,category,price,stock,size,discount,description,product_image)
    VALUES(?,?,?,?,?,?,?,?)";

    $stmt=mysqli_prepare($conn,$query);
    
    if(!$stmt){
        die("prepare failed ".mysqli_errno($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssddsdss",$product_name,$category,$price,$stock,$size,$discount,
    $description,$product_image);
    if(mysqli_stmt_execute($stmt)){
        echo "<script>
        alert ('Product added successfully');
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