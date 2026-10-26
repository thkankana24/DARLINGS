<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['customer'])) {
    header('Location: darl_loginpage.php?next=' . rawurlencode(basename($_SERVER['PHP_SELF'])));
    exit;
}
