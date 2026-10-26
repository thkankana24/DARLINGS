Remove.php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/sql/config.php';
require_once __DIR__ . '/sql/customer_session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("Location: cart.php");
    exit();
}

$cartId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$cartId) {
    $_SESSION['cart_message'] = "Invalid cart item.";
    header("Location: cart.php");
    exit();
}

$sessionId = session_id();

$stmt = $conn->prepare(
    "DELETE FROM cart
     WHERE id = ? AND session_id = ?"
);

$stmt->bind_param("is", $cartId, $sessionId);
$stmt->execute();

$removed = $stmt->affected_rows > 0;
$stmt->close();

$_SESSION['cart_message'] = $removed
    ? "Item removed from your bag."
    : "Cart item not found.";

header("Location: cart.php");
exit();
?>
