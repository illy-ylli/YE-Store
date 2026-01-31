<?php
session_start();
require_once 'config/Database.php';

// 4. Check if user is logged in AND admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// connect to DB
$db = new Database();
$conn = $db->getConnection();

// fetch products with creator info
$products = $conn->query("
    SELECT p.id, p.name, p.price, p.image_path, u.username AS created_by
    FROM products p
    LEFT JOIN users u ON p.created_by = u.id
")->fetchAll(PDO::FETCH_ASSOC);

// fetch support messagess
$supportMessages = $conn->query("
    SELECT * FROM support_messages ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?> 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Y/E Store</title>
<link rel="stylesheet" href="frontpage.css">
<style>
    body { font-family: Arial, sans-serif; }
    h2 { margin-top: 30px; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #222; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
</style>
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="frontpage.php"><img src="images/Logo.png" class="logo-icon" alt="Logo"></a>
    </div>
    <nav>
        <a href="frontpage.php">Home</a> |
        <a href="profile.php">Profile</a> |
        <a href="index.php">Logout</a>
    </nav>
</header>

<main style="padding: 20px;">
    <h1>Admin Dashboard</h1>

    <h2>Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td><img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="width:50px;"></td>
                <td><?= htmlspecialchars($p['created_by'] ?? 'Unknown') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Support Messages</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Received At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($supportMessages as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['id']) ?></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                <td><?= htmlspecialchars($m['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
