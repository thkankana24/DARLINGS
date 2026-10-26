<?php
session_start();
if(!isset($_SESSION["username"])){
    header("location: login.php");
    exit();
}
?>

<?php
include 'pages/header.php';
include 'pages/sidebar.php';
include 'pages/top-header.php';
?>

<?php
include 'sql/config.php';
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = '$id'";
$tabl = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($tabl);
?>


<link rel="stylesheet" href="css/style3.css">
<div class="container">
    <div class="page-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Product Management</h5>
        <a href="#" class="btn" id="btn-Publish" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fa-solid fa-plus"></i>Edit Details</a>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="addProductModalLabel">Edit New Details </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="sql/update.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="product_name"> Product Name<span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" value="<?php echo $row['productname'];?>" id="product_name" placeholder="Enter Product Name" class="form-control" required style="background-color: #fff;padding-left: 10px!important;">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="category">Category<span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option selected disabled value="">--- Select Category ---</option>
                                        <option value="Dress" <?php if($row['category'] == 'Dress') echo 'selected'; ?>>Dress</option>
                                        <option value="Shoes" <?php if($row['category'] == 'Shoes') echo 'selected'; ?>>Shoes</option>
                                        <option value="Bag" <?php if($row['category'] == 'Bag') echo 'selected'; ?>>Bag</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="price">Price<span class="text-danger">*</span></label>
                                    <input type="number" value="<?php echo $row['price'];?>" name="price" id="price" placeholder="Enter Price" class="form-control" min="0" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="product_size">Size</label>
                                   <select name="product_size" id="product_size" class="form-control">
                                    <option value="" disabled <?php if(empty($row['product_size'])) echo 'selected'; ?>>
                                        --- Select Size ---
                                    </option>
                                    <option value="XXL" <?php if($row['product_size'] == 'XXL') echo 'selected'; ?>>XXL</option>
                                    <option value="LG" <?php if($row['product_size'] == 'LG') echo 'selected'; ?>>LG</option>
                                    <option value="SM" <?php if($row['product_size'] == 'SM') echo 'selected'; ?>>SM</option>
                                </select>

                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="discount"> Discount (%)</label>
                                    <input type="number" value="<?php echo $row['discount'];?>" name="discount" id="discount" placeholder="Enter Discount" class="form-control" min="0" max="100">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="product_status">Product Status</label>
                                   <select name="product_status" id="product_status" class="form-control">
                                    <option value="" disabled <?php if(!isset($row['product_status']) || $row['product_status'] === '') echo 'selected'; ?>>
                                        --- Choose Product Status ---
                                    </option>
                                    <option value="1" <?php if((string)$row['product_status'] === '1') echo 'selected'; ?>>Active</option>
                                    <option value="0" <?php if((string)$row['product_status'] === '0') echo 'selected'; ?>>Inactive</option>
                                </select>

                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="product_description">Description</label>
                                    <textarea name="product_description" id="product_description" rows="5" placeholder="Write a short description highlighting key benefits and features" class="form-control">
                                        <?php echo $row['description'];?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-lg-4">
                            <label class="form-label fw-bold">Product Image</label>
                            <div class="image-upload-box p-3">
                                <input type="file" name="image" id="imageUpload" accept="image/jpeg,image/png,image/webp" hidden>
                                <label for="imageUpload" class="upload-label">
                                    <div id="uploadContent" style="<?php echo !empty($row['image']) ? 'display: none;' : ''; ?>">
                                        <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                        <h6> Upload Product Image</h6>
                                        <p>Click here to choose an image</p>
                                        <span>JPG, PNG, WEBP</span>
                                    </div>
                                    <img id="imagePreview" 
                                        src="<?php echo !empty($row['image']) ? 'upload/' . $row['image'] : ''; ?>" 
                                        alt="Product Preview" 
                                        style="<?php echo !empty($row['image']) ? 'display: block;' : 'display: none;'; ?>">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id" value="<?php echo $row['id'];?>">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit" id="btn-Publish1" class="btn">
                        <i class="fa-solid fa-plus"></i> Update Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include 'pages/footer.php'; ?>