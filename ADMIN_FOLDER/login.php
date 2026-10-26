<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: product.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlings | Admin Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f9e7ff, #fce4ef, #e9e5ff);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 38px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 18px 55px rgba(111, 70, 130, 0.15);
        }

        .brand {
            text-align: center;
            color: #d9468b;
            font-size: 31px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .subtitle {
            text-align: center;
            color: #858094;
            font-size: 14px;
            margin-top: 9px;
            margin-bottom: 28px;
        }

        h2 {
            color: #28243a;
            font-size: 22px;
            margin-bottom: 8px;
        }

        .description {
            color: #858094;
            font-size: 14px;
            margin-bottom: 24px;
        }

        label {
            display: block;
            color: #383247;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input, select {
            display: block;
            width: 100%;
            height: 47px;
            border: 1px solid #e7dfeb;
            border-radius: 11px;
            padding: 0 13px;
            margin-bottom: 19px;
            font-size: 14px;
            background: #fff;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #d9468b;
            box-shadow: 0 0 0 3px rgba(217, 70, 139, 0.10);
        }

        .login-button {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 11px;
            background: #d9468b;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 5px;
        }

        .login-button:hover {
            background: #c7357a;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #9991a4;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 27px 22px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="brand">Darlings</div>
        <p class="subtitle">Your store, your style.</p>

        <h2>Welcome back!</h2>
        <p class="description">
            Sign in to access your store dashboard.
        </p>

        <form action="admin/login_process.php" method="POST">

            <label for="role">Login as</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
            </select>

            <label for="email">Email address</label>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Enter your email"
                autocomplete="username"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
            >

            <button type="submit" class="login-button">
                Sign In
            </button>

        </form>

        <p class="footer">
            Darlings Admin Panel &copy; 2026
        </p>

    </div>

</body>
</html>