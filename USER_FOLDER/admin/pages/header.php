<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce || Admin</title>
    <link rel="stylesheet" href="css/style2.css">
    <?php if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'product.php'): ?>
        <link rel="stylesheet" href="css/style3.css?v=<?php echo (int) filemtime(__DIR__ . '/../css/style3.css'); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
