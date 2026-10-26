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


<link rel="stylesheet" href="css/style3.css">
<div class="container">
    <div class="page-header">
        <h5 class="mb-0 p-2 pb-3 pt-3" style="background-color:#FFF5F0; color:#F26024;">Manage Category</h5>
        <!-- <a href="#" class="btn" id="btn-Publish" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fa-solid fa-plus"></i>Add Product</a> -->
    
    <form action="">
        <div class="row mt-4">
            <div class="col-lg-6 mb-3">
                <label for="product_name"> Category Image<span class="text-danger">*</span></label>
                <input type="file" name="categoryimg" class="form-control" required style="background-color: #fff;padding-left: 10px!important;">
            </div>
            <div class="col-lg-6 mb-3">
                <label for="category">Category<span class="text-danger">*</span></label>
                <select name="category" id="category" class="form-control" required>
                    <option value="" disabled selected>
                        --- Select Category ---
                    </option>
                    <option value="Dress">Dress</option>
                    <option value="Shoes">Shoes</option>
                    <option value="Bag">Bag</option>
                </select>
            </div>
            <input type="submit" name="ok" value="Add" class="btn" id="btn-Publish" style="width:100px;margin-left:13px">
        </div>
    </form>

    </div>
</div>





<?php include 'pages/footer.php'; ?>