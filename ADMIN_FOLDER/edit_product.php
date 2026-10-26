<?php

include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';

include 'sql/config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM products_admin WHERE id = $id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($result);

?>

<link rel="stylesheet" href="css_file/product.css">
<link rel="stylesheet" href="css_file/edit-product.css">


<div class="edit-page">
    <div class="edit-page-header">
        <div>
            <h1>Edit Product</h1>
            <p> Update your product details, inventory and image.</p>
        </div>
        <a href="product.php" class="back-btn"> ← Back to Products</a>
    </div>
    <div class="edit-card">
        <form action="sql/update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <div class="edit-main">
                <div class="edit-details">
                    <h2>Product Details</h2>
                    <div class="form-group">
                        <label> Product Name </label>
                        <input type="text" name="product_name"  value="<?php echo htmlspecialchars($product['product_name']); ?>"  required>
                    </div>
                    <div class="form-group">
                        <label> Category  </label>
                        <select name="category" required>
                            <option value=""> Select Category </option>
                            <option value="Clothing"
                                <?php
                                if ($product['category'] == 'Clothing') echo 'selected';
                                ?>>
                                Clothing
                            </option>
                            <option value="Shoes"
                                <?php
                                if ($product['category'] == 'Shoes') echo 'selected';
                                ?>>
                                Shoes
                            </option>
                            <option value="Accessories"
                                <?php
                                if ($product['category'] == 'Accessories') echo 'selected';
                                ?>>
                                Accessories
                            </option>
                            <option value="Beauty"
                                <?php
                                if ($product['category'] == 'Beauty') echo 'selected';
                                ?>>
                                Beauty
                            </option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label> Price </label>
                            <input type="number"
                                name="price"
                                value="<?php echo $product['price']; ?>"
                                min="0"
                                step="0.01"
                                required>
                        </div>
                        <div class="form-group">
                            <label>  Stock Quantity  </label>
                            <input type="number"
                                name="stock"
                                value="<?php echo $product['stock']; ?>"
                                min="0"
                                required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label> Size </label>
                            <select name="size" required>
                                <option value="">Select Size</option>
                                <option value="XS"
                                    <?php
                                    if ($product['size'] == 'XS') echo 'selected';
                                    ?>>
                                    XS
                                </option>
                                <option value="S"
                                    <?php
                                    if ($product['size'] == 'S') echo 'selected';
                                    ?>>
                                    S
                                </option>
                                <option value="M"
                                    <?php
                                    if ($product['size'] == 'M') echo 'selected';
                                    ?>>
                                    M
                                </option>
                                <option value="L"
                                    <?php
                                    if ($product['size'] == 'L') echo 'selected';
                                    ?>>
                                    L
                                </option>
                                <option value="XL"
                                    <?php
                                    if ($product['size'] == 'XL') echo 'selected';
                                    ?>>
                                    XL
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>  Discount </label>
                            <div class="discount-input">
                                <input type="number"
                                    name="discount"
                                    value="<?php echo $product['discount']; ?>"
                                    min="0"
                                    max="100">
                                <span>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            Description
                        </label>
                        <textarea name="description"
                            rows="6"
                            placeholder="Enter product description..."><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                </div>
                <div class="edit-image">
                    <h2>Product Image</h2>
                    <div class="current-image">
                        <?php if (!empty($product['product_image'])) { ?>
                            <img id="previewImage"
                                src="upload/<?php echo htmlspecialchars($product['product_image']); ?>"
                                alt="Product">
                        <?php } else { ?>
                            <div class="no-image">  No Image </div>
                        <?php } ?>
                    </div>
                    <label class="image-upload-btn">
                        📷 Change Product Image
                        <input type="file"
                            name="product_image"
                            accept="image/*"
                            onchange="previewProductImage(this)">
                    </label>
                    <p class="image-info">
                        JPG, PNG or JPEG
                    </p>
                </div>
            </div>
            <div class="edit-buttons">
                <a href="product.php"
                    class="cancel-btn">

                    Cancel
                </a>
                <button type="submit"
                    name="update"
                    class="save-btn">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewProductImage(input) {

        const preview = document.getElementById("previewImage");

        if (input.files && input.files[0]) {

            const reader = new FileReader();

            reader.onload = function(e) {

                preview.src = e.target.result;

            };

            reader.readAsDataURL(input.files[0]);

        }

    }
</script>

<?php
include 'PAGES/footer.php';
?>