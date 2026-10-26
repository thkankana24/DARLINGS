

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


<div class="container mt-5">
    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <div class="card1">
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <img src="img/manifest.png" alt="..." height="60px" width="60px" class="icon-img">
                    </div>
                    <div class="col-lg-7 col-sm-12">
                        <h5 class="pt-4">Orders</h5>
                        <p>5487</p>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-lg-3 col-sm-12">
            <div class="card1">
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <img src="img/smartphone.png" alt="..." height="60px" width="60px" class="icon-img">
                    </div>
                    <div class="col-lg-7 col-sm-12">
                        <h5 class="pt-4">Payments</h5>
                        <p>85967</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-12">
            <div class="card1">
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <img src="img/cubes.png" alt="..." height="60px" width="60px" class="icon-img">
                    </div>
                    <div class="col-lg-7 col-sm-12">
                        <h5 class="pt-4">Products</h5>
                        <p>24563</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-12">
            <div class="card1">
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <img src="img/cancelled.png" alt="..." height="60px" width="60px" class="icon-img">
                    </div>
                    <div class="col-lg-7 col-sm-12">
                        <h5 class="pt-4">Cancel</h5>
                        <p>563</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





<?php include 'pages/footer.php';?>