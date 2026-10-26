<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("location:login.php");
    exit();
}
include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';

?>

<link rel="stylesheet" href="css_file/product.css">
<link rel="stylesheet" href="css_file/2popup.css">
<div class="container product-section">
    <div class="row">
        <div class="col-12">
            <div class="product">
                <div>
                    <h1>Products</h1>
                    <p>
                        Manage your store products, add new items,
                        update details and keep track of inventory.
                    </p>
                </div>
                <button class="add-product-btn" onclick="openProductPopup()">
                    + Add New Product
                </button>
                <div id="productPopup" class="popup-overlay">
                    <div class="product-popup">
                        <div class="popup-header">
                            <h2>Add New Product</h2>
                            <button type="button" class="close-btn" onclick="closeProductPopup()">
                                &times;
                            </button>
                        </div>
                        <form action="sql/2product.php" method="POST" enctype="multipart/form-data">
                            <div class="product-main-area">
                                <div class="product-details">
                                    <div class="form-group">
                                        <label> Product Name</label>
                                        <input type="text" name="product_name" placeholder="Enter product name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label> Category </label>
                                        <select name="category" required>
                                            <option value=""> Select Category </option>
                                            <option value="Clothing"> Clothing </option>
                                            <option value="Shoes"> Shoes </option>
                                            <option value="Accessories"> Accessories </option>
                                            <option value="Beauty"> Beauty </option>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>
                                                Price
                                            </label>
                                            <input type="number" name="price" placeholder="₹ 0.00"
                                                min="0"
                                                step="0.01"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                Stock Quantity
                                            </label>
                                            <input type="number" name="stock" placeholder="Enter quantity"
                                                min="0"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label> Size </label>
                                            <select name="size" required>
                                                <option value=""> Select Size </option>
                                                <option value="XS"> XS </option>
                                                <option value="S"> S </option>
                                                <option value="M"> M </option>
                                                <option value="L"> L </option>
                                                <option value="XL"> XL </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                Discount
                                            </label>
                                            <div class="discount-input">
                                                <input type="number" name="discount" placeholder="Enter discount" min="0"
                                                    max="100">
                                                <span>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-image-section">
                                    <div class="form-group">
                                        <label> Product Image</label>
                                        <div class="image-upload">
                                            <input type="file" name="product_image" accept="image/*">
                                            <div class="upload-content">
                                                <div class="upload-icon">
                                                    📷
                                                </div>
                                                <span> Upload Product Image </span>
                                                <small> JPG, PNG or JPEG </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group description-group">
                                <label> Description </label>
                                <textarea name="description" placeholder="Enter product description..." rows="4"></textarea>
                            </div>
                            <div class="popup-footer">
                                <button type="button" class="cancel-btn" onclick="closeProductPopup()">
                                    Cancel
                                </button>
                                <button type="submit" name="done" class="save-btn"> Save Product </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .table-body table,th,td{
        font-size: 13px;
    
    }
    .sno{
        padding-left:15px!important;

    }
    table a{
        text-decoration: none;
    }
</style>
<table class="table table-striped table-bordered dataTable">
    <thead>
        <tr>
            <th>Sno</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Size</th>
            <th>Discount</th>
            <th>Description</th>
            <th>Product Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'sql/config.php';
        $query = "select * from products_admin";
        $table = mysqli_query($conn, $query);
        if (mysqli_num_rows($table) > 0) {
            while ($row = mysqli_fetch_array($table)) {
        ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['size']; ?></td>
                    <td><?php echo $row['discount']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <img src="upload/<?php echo $row['product_image']; ?>" alt="..." height="40px" width="60px"
                            class="img-thumbnail">
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id'];?>">
                            <img src="images/edit.png" alt="edit" height="20px" width="20px">
                        </a>
                        <a href="sql/delete_product.php?id=<?php echo $row['id'];?>">
                            <img src="images/delete.png" alt="delete" height="20px" width="20px">
                        </a>
                    </td>

                </tr>
        <?php

            }
        }
        ?>
    </tbody>
</table>

<script src="js_file/something.js"></script>
<script src="js_file/product.js"></script>
<?php
include 'PAGES/footer.php';
?>