<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require_once __DIR__ . '/../sql/config.php';

function respond(int $status, array $data): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    respond(200, ['customer' => $_SESSION['customer'] ?? null]);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(405, ['message' => 'Method not allowed.']);
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) respond(400, ['message' => 'Invalid request.']);
$action = $input['action'] ?? '';

if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    respond(200, ['message' => 'Signed out.']);
}

if ($action === 'signup') {
    $name = trim((string)($input['name'] ?? ''));
    $email = strtolower(trim((string)($input['email'] ?? '')));
    $password = (string)($input['password'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        respond(422, ['message' => 'Enter your name, a valid email, and a password with at least 6 characters.']);
    }
    $check = mysqli_prepare($conn, 'SELECT id FROM customers WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($check, 's', $email);
    mysqli_stmt_execute($check);
    if (mysqli_stmt_get_result($check)->num_rows > 0) respond(409, ['message' => 'An account with this email already exists. Please sign in.']);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = mysqli_prepare($conn, 'INSERT INTO customers (name, email, password_hash) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($insert, 'sss', $name, $email, $hash);
    if (!mysqli_stmt_execute($insert)) respond(500, ['message' => 'Your account could not be created. Please try again.']);
    respond(201, ['message' => 'Account created. Please sign in to continue.']);
}

if ($action === 'login') {
    $email = strtolower(trim((string)($input['email'] ?? '')));
    $password = (string)($input['password'] ?? '');
    $stmt = mysqli_prepare($conn, 'SELECT id, name, email, password_hash FROM customers WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$customer || !password_verify($password, $customer['password_hash'])) respond(401, ['message' => 'Email or password is incorrect.']);
    session_regenerate_id(true);
    $_SESSION['customer'] = ['id' => (int)$customer['id'], 'name' => $customer['name'], 'email' => $customer['email']];
    respond(200, ['customer' => $_SESSION['customer']]);
}
respond(400, ['message' => 'Unknown action.']);
