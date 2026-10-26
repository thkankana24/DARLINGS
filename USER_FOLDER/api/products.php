
<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/../sql/config.php';

// Fetch products from database
$query = "SELECT id, product_name, category, price, stock,
                 size, discount, description, product_image
          FROM products_admin
          ORDER BY id DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Unable to load products'
    ]);
    exit;
}

$products = [];

while ($row = mysqli_fetch_assoc($result)) {

    $products[] = [
        'id' => (int) $row['id'],
        'name' => $row['product_name'],
        'category' => $row['category'],
        'price' => (float) $row['price'],
        'stock' => (int) $row['stock'],
        'sizes' => !empty($row['size'])
            ? array_map('trim', explode(',', $row['size']))
            : ['One size'],
        'discount' => (float) $row['discount'],
        'description' => $row['description'],
       'image' => '/darlings/ADMIN_FOLDER/upload/' . rawurlencode(basename($row['product_image']))

    ];
}
echo json_encode($products, JSON_UNESCAPED_SLASHES);
?>
