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

include 'config.php';

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $size = $_POST['size'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];


    // Check if a new image was selected
    if (!empty($_FILES['product_image']['name'])) {

        $product_image = $_FILES['product_image']['name'];
        $tmp_name = $_FILES['product_image']['tmp_name'];

        // Upload image into sql/upload/
        move_uploaded_file(
            $tmp_name,
            'upload/' . $product_image
        );


        // Update everything including image
        $query = "UPDATE products_admin SET
                    product_name='$product_name',
                    category='$category',
                    price='$price',
                    stock='$stock',
                    size='$size',
                    discount='$discount',
                    description='$description',
                    product_image='$product_image'
                  WHERE id='$id'";

    } else {

        // No new image selected
        // Keep the old image
        $query = "UPDATE products_admin SET
                    product_name='$product_name',
                    category='$category',
                    price='$price',
                    stock='$stock',
                    size='$size',
                    discount='$discount',
                    description='$description'
                  WHERE id='$id'";
    }


    // Execute update
    $updated_query = mysqli_query($conn, $query);


    if ($updated_query) {

        echo "<script>
                alert('Data updated successfully');
                window.location='../product.php';
              </script>";

    } else {

        echo "Update Error: " . mysqli_error($conn);

    }

}

?>