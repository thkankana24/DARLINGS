<?php
if(isset($_POST['edit'])){
    require_once __DIR__ . '/../../sql/config.php';

$id = $_POST['id'];
$product_name = $_POST['product_name'];
$category = $_POST['category'];
$price = $_POST['price'];
$product_size = $_POST['product_size'];
$discount = $_POST['discount'];
$product_status = $_POST['product_status'];
$product_description = $_POST['product_description'];
$image = $_FILES['image']['name'];


if(!empty($_FILES['image']['name'])){
    
$image = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];
move_uploaded_file($tmp_name, '../upload/' . $image);

    $query = "UPDATE products SET productname='$product_name', category='$category', price='$price',
    product_size='$product_size', discount='$discount', product_status='$product_status', 
    description='$product_description', image='$image' WHERE id = '$id'";
}
else{
    $query = "UPDATE products SET productname='$product_name', category='$category', price='$price',
    product_size='$product_size', discount='$discount', product_status='$product_status', 
    description='$product_description' WHERE id = '$id'";
}

$upadted_query = mysqli_query($conn,$query);

if($upadted_query){
    echo "<script>
        alert('Data Updated Successfully')
        window.location = '../product.php';
    </script>";
}else{
    echo "<script>
        alert('Update Failed');
    </script>";
    }
}

?>
