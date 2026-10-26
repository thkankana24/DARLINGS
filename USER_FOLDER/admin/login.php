<?php
require_once __DIR__ . '/../sql/config.php';
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST['password'] ?? '';
    
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'"; 
    $table = mysqli_query($conn, $sql);

    if ($table && mysqli_num_rows($table) === 1) {
        $_SESSION["username"] = $username;
        header("Location: index.php"); 
        exit();
    } else {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User|| Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .btn-theme {
            background-color: #E75480;
            color: #fff;
            font-weight: 600;
        }
        .btn-theme:hover {
            background-color: #E75480;
            color: #fff;
        }
        .form-control:focus {
            border-color: #E75480;
            box-shadow: 0 0 0 0.25rem rgba(231, 84, 128, 0.25);
        }
        .brand-title {
            color: #E75480;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card login-card p-4 mx-auto bg-white">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="brand-title">Darlings</h2>
                    <p class="text-muted small">User Login</p>
                    <?php if (!empty($error)) { echo "<div class='alert alert-danger py-1 small mt-2'>$error</div>"; } ?>
                </div>

                <form action="" method="POST">
                    <!-- Username Input Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label text-secondary small fw-bold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="username" name="username" placeholder="Enter Username" required>
                        </div>
                    </div>

                    <!-- Password Input Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label text-secondary small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" class="form-control border-start-0 bg-light" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4 text-muted small">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="#" class="text-decoration-none text-secondary">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <input type="submit" class="btn btn-theme w-100 py-2" value="Login">
                </form>
            </div>
        </div>
    </div>

</body>
</html>
