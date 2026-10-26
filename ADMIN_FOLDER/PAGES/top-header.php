<link rel="stylesheet" href="css_file/topheader_style.css">
<link rel="stylesheet" href="css_file/admin_login.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<div class="col-lg-10 col-sm-12 right-top">
    <div class="row top-header">

        <form action="" class="col-lg-6">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="globalSearch" type="text" placeholder="Search products, orders, customers...."
                class="form-control">
        </form>

        <!-- Admin -->
        <div class="col-lg-3 col-sm-12 ms-auto rightside-top">
            <div class="admin-user" onclick="toggleUserMenu()">

                <div class="admin-avatar">
                    👤
                </div>

                <div class="admin-name">
                    <strong id="loggedUserName">
                        <?php
                        echo isset($_SESSION['admin_name'])
                            ? htmlspecialchars($_SESSION['admin_name'])
                            : 'Account';
                        ?>
                    </strong><span>▼</span>
                </div>

                <!-- User selection popup -->
                <div class="user-popup" id="userPopup">

                    <div class="popup-title">
                        Login as
                    </div>

                    <div class="user-option" onclick="openLogin('admin', event)">
                        <div class="user-icon">👤</div>
                        <div>
                            <strong>Admin</strong>
                            <small>Administrator</small>
                        </div>
                    </div>

                    <div class="user-option" onclick="openLogin('manager', event)">
                        <div class="user-icon">👤</div>
                        <div>
                            <strong>Manager</strong>
                            <small>Store Manager</small>
                        </div>

                    </div>
                    <a href="logout.php" class="logout-link">
                        <span>↪</span> Logout
                    </a>

                </div>

            </div>


            <!-- LOGIN POPUP -->

            <div class="login-overlay" id="loginOverlay">

                <div class="login-box">

                    <button class="close-login" onclick="closeLogin()">×</button>

                    <div class="login-avatar">
                        👤
                    </div>

                    <h2 id="loginTitle">Admin Login</h2>

                    <p>Login to your Darlings account</p>

                    <form id="loginForm" action="admin/login_process.php" method="POST">


                        <label for="loginEmail">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="loginEmail"
                            form="loginForm"
                            placeholder="Enter email"
                            required>

                        <label for="loginPassword">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="loginPassword"
                            form="loginForm"
                            placeholder="Enter password"
                            required>

                        <button type="submit" name="role" id="loginSubmit" value="admin" class="login-button">
                            Login
                        </button>

                    </form>

                    <p id="loginMessage"></p>

                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedUser = "";


        /* OPEN/CLOSE USER DROPDOWN */

        function toggleUserMenu() {

            const popup = document.getElementById("userPopup");

            popup.classList.toggle("active");
        }


        /* OPEN LOGIN */

        function openLogin(user, event) {

            event.stopPropagation();

            selectedUser = user;
            document.getElementById("loginSubmit").value = user;

            document.getElementById("userPopup").classList.remove("active");

            const overlay = document.getElementById("loginOverlay");

            const title = document.getElementById("loginTitle");

            if (user === "admin") {

                title.innerText = "Admin Login";

            } else {

                title.innerText = "Manager Login";

            }

            overlay.classList.add("active");
        }


        /* CLOSE LOGIN */

        function closeLogin() {

            document.getElementById("loginOverlay")
                .classList.remove("active");

        }


        /* LOGIN */

        function loginUser(event) {

            event.preventDefault();

            const email =
                document.getElementById("loginEmail").value;

            const password =
                document.getElementById("loginPassword").value;


            /*
               TEMPORARY FRONT-END LOGIN

               Later we will connect this to
               PHP + MySQL.
            */

            if (selectedUser === "admin") {

                if (
                    email === "admin@darlings.com" &&
                    password === "admin123"
                ) {

                    document.getElementById("loggedUserName")
                        .innerText = "Admin";

                    closeLogin();

                    alert("Admin logged in successfully!");

                } else {

                    alert("Invalid Admin email or password.");

                }

            } else if (selectedUser === "manager") {

                if (
                    email === "manager@darlings.com" &&
                    password === "manager123"
                ) {

                    document.getElementById("loggedUserName")
                        .innerText = "Manager";

                    closeLogin();

                    alert("Manager logged in successfully!");

                } else {

                    alert("Invalid Manager email or password.");

                }

            }

        }
    </script>