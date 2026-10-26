<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/sql/config.php';
require_once __DIR__ . '/sql/customer_session.php';

$sessionId = session_id();

$stmt = $conn->prepare(
    "DELETE FROM cart WHERE session_id = ?"
);

$stmt->bind_param("s", $sessionId);
$stmt->execute();
$stmt->close();

$_SESSION['cart_message'] = "Your cart has been cleared.";

header("Location: cart.php");
exit();
?>
