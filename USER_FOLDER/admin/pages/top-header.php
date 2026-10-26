<div class="col-lg-10 col-sm-12 right-top">
    <div class="row align-items-center justify-content-between h-100 px-4">
        <div class="col-lg-5 col-sm-6">
            <form action="" class="position-relative mb-0">
                <input type="text" placeholder="Search or type a command" class="form-control">
                <i class="fa-solid fa-magnifying-glass"></i>
            </form>
        </div>
        <div class="col-lg-4 col-sm-6 d-flex align-items-center justify-content-end gap-3">

            <div class="icon-box">
                <i class="fa-regular fa-bell"></i>
            </div>
            <div class="icon-box">
                <i class="fa-regular fa-pen-to-square"></i>
            </div>
            <div class="user-avatar-trigger" data-bs-toggle="offcanvas" data-bs-target="#accountOffcanvas"
                aria-controls="accountOffcanvas">
                <img src="img/user.png" alt="User" class="rounded-circle" width="40" height="40">
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="accountOffcanvas" aria-labelledby="accountOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="accountOffcanvasLabel">Account Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="text-center mb-4">
                <img src="img/user.png" alt="User" class="rounded-circle mb-3 shadow-sm" width="80" height="80">
                <h6 class="mb-1 fw-bold">

                    <?php echo $_SESSION["username"]; ?>
                </h6>

            </div>

            <hr>

            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action py-3 border-0 rounded mb-1"><i
                        class="fa-solid fa-user me-3 text-primary"></i> My Profile</a>
                <a href="#" class="list-group-item list-group-item-action py-3 border-0 rounded mb-1"><i
                        class="fa-solid fa-gear me-3 text-primary"></i> Account Settings</a>
                <a href="#" class="list-group-item list-group-item-action py-3 border-0 rounded mb-1"><i
                        class="fa-solid fa-shield-halved me-3 text-primary"></i> Security</a>
            </div>

            <div class="mt-4 pt-4 border-top">
                <a href="logout.php" class="btn btn-outline-danger w-100 py-2"><i
                        class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>