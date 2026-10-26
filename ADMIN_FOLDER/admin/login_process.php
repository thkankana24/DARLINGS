
<?php

session_start();

include "../sql/config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];


    /* Find the selected user */

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM admin_users
         WHERE email = ? AND role = ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $email,
        $role
    );


    mysqli_stmt_execute($stmt);


    $result = mysqli_stmt_get_result($stmt);


    /* Check whether user exists */

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);


        /* Check password */

        if ($password === $user["password"]) {

            /* Store user information in session */

            $_SESSION["admin_id"] = $user["id"];

            $_SESSION["admin_name"] = $user["name"];

            $_SESSION["admin_email"] = $user["email"];

            $_SESSION["admin_role"] = $user["role"];


            /* Login successful */

            header("Location: ../product.php");

            exit();

        } else {

            echo "<script>
                    alert('Invalid password');
                    window.history.back();
                  </script>";
        }

    } else {

        echo "<script>
                alert('Invalid email or user type');
                window.history.back();
              </script>";
    }

}

?>