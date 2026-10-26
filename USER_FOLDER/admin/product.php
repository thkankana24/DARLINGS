<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

include 'pages/header.php';
include 'pages/sidebar.php';
include 'pages/top-header.php';
?>

<link rel="stylesheet" href="css/style3.css?v=<?php echo filemtime(__DIR__ . '/css/style3.css'); ?>">

<div class="container">
    <div class="page-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Product Management</h5>
        <a href="#" class="btn" id="btn-Publish" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fa-solid fa-plus"></i> Add Product
        </a>
    </div>
</div>

<!-- =========================================
     PRODUCT TABLE
========================================= -->
<div class="container mt-3 table-body">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sno.</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Size</th>
                            <th>Discount</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Adjust this path to match where config.php really lives.
                        // The form below posts to "sql/products.php", so sql/ is
                        // assumed to be next to this file.
                        include 'sql/config.php';

                        // Support common database variable names and provide a safe fallback
                        // when the configuration file does not define $config.
                        if (!isset($config)) {
                            $config = $con ?? $conn ?? null;
                        }

                        if (!$config) {
                            $config = mysqli_connect('localhost', 'root', '', 'ecommerce');
                        }

                        if (!$config) {
                            die('Database connection failed.');
                        }

                        $query = "SELECT * FROM products";
                        $table = mysqli_query($config, $query);

                        if ($table && mysqli_num_rows($table) > 0) {
                            while ($row = mysqli_fetch_assoc($table)) {
                        ?>
                                <tr>
                                    <td class="sno"><?php echo (int)$row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['productname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_size']); ?></td>
                                    <td><?php echo htmlspecialchars($row['discount']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" alt="Product" height="40" width="60" class="img-thumbnail">
                                    </td>
                                    <td><?php echo $row['product_status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo (int)$row['id']; ?>">
                                            <img src="img/compose.png" alt="Edit" height="20" width="20">
                                        </a>
                                        <a href="sql/delete.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this product?');">
                                            <img src="img/delete.png" alt="Delete" height="20" width="20">
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- =========================================
     ADD PRODUCT MODAL
========================================= -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <small class="text-muted">Enter product details below</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="sql/products.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="product_name">Product Name<span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="product_name" placeholder="Enter Product Name" class="form-control" required>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="category">Category<span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="" disabled selected>--- Select Category ---</option>
                                        <option value="Dress">Dress</option>
                                        <option value="Shoes">Shoes</option>
                                        <option value="Bag">Bag</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="price">Price<span class="text-danger">*</span></label>
                                    <input type="number" name="price" id="price" placeholder="Enter Price" class="form-control" min="0" required>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="product_size">Size</label>
                                    <select name="product_size" id="product_size" class="form-control">
                                        <option value="" disabled selected>--- Select Size ---</option>
                                        <option value="XXL">XXL</option>
                                        <option value="LG">LG</option>
                                        <option value="SM">SM</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" name="discount" id="discount" placeholder="Enter Discount" class="form-control" min="0" max="100">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="product_status">Product Status</label>
                                    <select name="product_status" id="product_status" class="form-control">
                                        <option value="" disabled selected>--- Choose Product Status ---</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label for="product_description">Description</label>
                                    <textarea name="product_description" id="product_description" rows="5" placeholder="Write a short description highlighting key benefits and features" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label fw-bold">Product Image</label>
                            <div class="image-upload-box">
                                <input type="file" name="image" id="imageUpload" accept="image/jpeg,image/png,image/webp" hidden>
                                <label for="imageUpload" class="upload-label">
                                    <div id="uploadContent">
                                        <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                        <h6>Upload Product Image</h6>
                                        <p>Click here to choose an image</p>
                                        <span>JPG, PNG, WEBP</span>
                                    </div>
                                    <img id="imagePreview" src="" alt="Product Preview" style="display: none;">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="okk" id="btn-Publish1" class="btn">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image preview -->
<script>
document.getElementById('imageUpload').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var preview = document.getElementById('imagePreview');
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
    document.getElementById('uploadContent').style.display = 'none';
});
</script>

<?php include 'pages/footer.php'; ?>