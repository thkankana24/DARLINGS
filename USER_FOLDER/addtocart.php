<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to your database
require_once __DIR__ . '/sql/config.php';

// Check database connection
if (!isset($conn) || !($conn instanceof mysqli)) {
    exit("Database connection failed. Check sql/config.php.");
}

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: products.php");
    exit();
}

// Get product ID, quantity and size from products.php
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$size = trim($_POST['size'] ?? '');

// Validate submitted values
if (!$productId || !$quantity || $quantity < 1 || $quantity > 99) {
    $_SESSION['cart_message'] = "Invalid product or quantity.";
    header("Location: products.php");
    exit();
}

if (strlen($size) > 20) {
    $_SESSION['cart_message'] = "Invalid product size.";
    header("Location: products.php");
    exit();
}

// Identify this visitor's cart session
$sessionId = session_id();

// Find the product in your products_admin table
$stmt = $conn->prepare(
    "SELECT id, stock
     FROM products_admin
     WHERE id = ?"
);

if (!$stmt) {
    exit("Product query failed: " . $conn->error);
}

$stmt->bind_param("i", $productId);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Check whether the product exists
if (!$product) {
    $_SESSION['cart_message'] = "Product not found.";
    header("Location: products.php");
    exit();
}

// Check available stock
$stock = (int)$product['stock'];

if ($stock < 1) {
    $_SESSION['cart_message'] = "Sorry, this product is out of stock.";
    header("Location: products.php");
    exit();
}

// Check whether the product and selected size are already in the cart
$stmt = $conn->prepare(
    "SELECT id, quantity
     FROM cart
     WHERE session_id = ?
       AND product_id = ?
       AND size = ?"
);

if (!$stmt) {
    exit("Cart query failed: " . $conn->error);
}

$stmt->bind_param("sis", $sessionId, $productId, $size);
$stmt->execute();

$existingItem = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Calculate the new quantity
$currentQuantity = $existingItem
    ? (int)$existingItem['quantity']
    : 0;

$newQuantity = $currentQuantity + $quantity;

// Do not allow the quantity to exceed stock
if ($newQuantity > $stock) {
    $_SESSION['cart_message'] =
        "Only " . $stock . " item(s) are available in stock.";

    header("Location: products.php");
    exit();
}

// Update an existing cart item or insert a new one
if ($existingItem) {

    $cartId = (int)$existingItem['id'];

    $stmt = $conn->prepare(
        "UPDATE cart
         SET quantity = ?
         WHERE id = ? AND session_id = ?"
    );

    if (!$stmt) {
        exit("Cart update failed: " . $conn->error);
    }

    $stmt->bind_param(
        "iis",
        $newQuantity,
        $cartId,
        $sessionId
    );

} else {

    $stmt = $conn->prepare(
        "INSERT INTO cart
            (session_id, product_id, quantity, size)
         VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        exit("Cart insert failed: " . $conn->error);
    }

    $stmt->bind_param(
        "siis",
        $sessionId,
        $productId,
        $quantity,
        $size
    );
}

// Save the cart item
if ($stmt->execute()) {
    $_SESSION['cart_message'] = "Product added to your bag!";
} else {
    $_SESSION['cart_message'] =
        "Unable to add this product. Please try again.";
}

$stmt->close();

// Return to the cart page
header("Location: cart.php");
exit();
?>