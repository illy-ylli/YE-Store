<?php
session_start();
require_once 'config/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// merr statistikat
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];
$totalMessages = $conn->query("SELECT COUNT(*) as count FROM support_messages")->fetch()['count'];
$unreadMessages = $conn->query("SELECT COUNT(*) as count FROM support_messages WHERE is_read = 0")->fetch()['count'];

// merr produktet
$products = $conn->query("SELECT p.*, u.username AS created_by FROM products p LEFT JOIN users u ON p.created_by = u.id ORDER BY p.created_at DESC")->fetchAll();

// merr mesazhet
$messages = $conn->query("SELECT * FROM support_messages ORDER BY created_at DESC")->fetchAll();

if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $msgId = (int)$_GET['mark_read'];
    $stmt = $conn->prepare("UPDATE support_messages SET is_read = 1 WHERE id = :id");
    $stmt->execute([':id' => $msgId]);
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Y/E Store</title>
<link rel="stylesheet" href="frontpage.css">
<style>
    .admin-header { background: white; height: 80px; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .admin-header nav { display: flex; align-items: center; gap: 15px; color: #666; }
    .admin-header nav a { color: #666; text-decoration: none; padding: 8px 15px; border-radius: 4px; }
    .admin-header nav a:hover { background-color: #f0f0f0; color: #222; }
    main { padding: 20px; max-width: 1200px; margin: 0 auto; }
    .stats { display: flex; gap: 20px; margin: 20px 0; }
    .stat-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex: 1; text-align: center; }
    .stat-box h3 { margin: 0; color: #666; }
    .stat-box .number { font-size: 2em; font-weight: bold; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #222; color: white; }
    tr:nth-child(even) { background: #f2f2f2; }
    .unread { background: #fff3cd; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9em; }
    .btn-edit { background: #007bff; color: white; }
    .btn-delete { background: #dc3545; color: white; }
    .btn-read { background: #28a745; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; display: inline-block; }
    .badge { padding: 3px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; }
    .badge-top { background: #ffc107; color: #000; }
    .badge-new { background: #17a2b8; color: white; }
    .admin-nav { margin: 20px 0; }
    .admin-nav a { margin-right: 15px; padding: 8px 15px; background: #222; color: white; text-decoration: none; border-radius: 4px; }
    .admin-nav a:hover { background: #444; }
</style>
</head>
<body>
<header class="admin-header">
    <div class="logo">
        <a href="frontpage.php"><img src="images/Logo.png" style="height:120px;" alt="Logo"></a>
    </div>
    <nav>
        <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> (Admin)</span> |
        <a href="index.php">Logout</a>
    </nav>
</header>

<main>
    <h1>Admin Dashboard</h1>
    
    <div class="admin-nav">
        <a href="admin_add_product.php">Add New Product</a>
        <a href="#products">Products</a>
        <a href="#messages">Messages</a>
    </div>
    
    <div class="stats">
        <div class="stat-box"><h3>Products</h3><div class="number"><?= $totalProducts ?></div></div>
        <div class="stat-box"><h3>Users</h3><div class="number"><?= $totalUsers ?></div></div>
        <div class="stat-box"><h3>Messages</h3><div class="number"><?= $totalMessages ?></div><small><?= $unreadMessages ?> unread</small></div>
    </div>
    
    <h2 id="products">Products (<?= $totalProducts ?>)</h2>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Status</th><th>Created By</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($products as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td>
                    <?php if($p['is_top_product']): ?><span class="badge badge-top">Top</span><?php endif; ?>
                    <?php if($p['is_new_arrival']): ?><span class="badge badge-new">New</span><?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['created_by'] ?? 'Unknown') ?></td>
                <td>
                    <button class="btn btn-edit" onclick="location.href='admin_edit_product.php?id=<?= $p['id'] ?>'">Edit</button>
                    <button class="btn btn-delete" onclick="if(confirm('Delete product #<?= $p['id'] ?>?')) location.href='admin_delete_product.php?id=<?= $p['id'] ?>'">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2 id="messages">Support Messages (<?= $unreadMessages ?> unread)</h2>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Received</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($messages as $m): ?>
            <tr class="<?= $m['is_read'] ? '' : 'unread' ?>">
                <td><?= $m['id'] ?></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= substr(htmlspecialchars($m['message']), 0, 100) . (strlen($m['message']) > 100 ? '...' : '') ?></td>
                <td><?= $m['is_read'] ? 'Read' : 'Unread' ?></td>
                <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                <td>
                    <?php if(!$m['is_read']): ?><a href="?mark_read=<?= $m['id'] ?>" class="btn-read">Mark Read</a><?php endif; ?>
                    <button class="btn btn-delete" onclick="if(confirm('Delete message #<?= $m['id'] ?>?')) location.href='admin_delete_message.php?id=<?= $m['id'] ?>'">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>