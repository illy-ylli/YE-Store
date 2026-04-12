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
<title>Paneli i Administratorit - Y/E Store</title>
<link rel="stylesheet" href="frontpage.css">
<style>
    .admin-header { background: white; height: 80px; padding: 0 30px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(0,0,0,0.1);  position: sticky; top: 0; z-index: 1000; }
    .admin-header nav { display: flex; align-items: center; gap: 20px; color: #373535; }
    .admin-header nav a { color: #1b1a1a; text-decoration: none; padding: 8px 18px; border-radius: 25px; transition: all 0.3s ease; font-weight: 500;}
    .admin-header nav a:hover { background-color: rgba(255,255,255,0.2); transform: translateY(-2px); }
    .admin-heaver nav span {background: rgba(255,255,255,0.15); padding: 8px 15px; border-radius: 25px;}
    main { padding: 30px; max-width: 1400px; margin: 0 auto; }
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin: 30px 0; }
    .stat-box { background: linear-gradient(135deg, #fff 0%, #f8f9fa 100% ); padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.88); text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid rgba(0,0,0,0.05);}
    .stat-box:hover {transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15);}
    .stat-box h3 { margin: 0; color: #666; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;}
    .stat-box .number { font-size: 2.5em; font-weight: bold; margin: 15px 0; color: #1a1a2e }
    .table-container {overflow-x: auto; margin: 20px 0; background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05)}
    table { width: 100%; border-collapse: collapse;}
    th, td { border-bottom: 1px solid #eee; padding: 12px 15px; text-align: left; }
    th { background: liear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #1a1a2e; font-weight: 600; position: sticky; top:0; }
    tr:hover{background-color: #f8f9fa;}
    .btn { padding: 8px 16px; border: none; border-radius: 8px; cursor: pointer; font-size: 0.85em; font-weight: 500; transition: all 0.3s ease; margin: 0 3px; }
    .btn-edit { background: #4CAF50; color: white; }
    .btn-edit:hover {background: #45a049; transform: scale(1.05);}
    .btn-delete { background: #f44336; color: white; }
    .btn-delete:hover{background: #da190b; transform: scale(1.05);}
    .btn-read { background: #2196F3; color: white; text-decoration: none; padding: 6px 12px; border-radius: 6px; display: inline-block; transition: all 0.3s ease;}
    .btn-read:hover{background:#0b7dda; transform: all 0.3 ease;}
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75em; font-weight: bold; display: inline-block; }
    .badge-top { background: #ff9800; color: #fff; }
    .badge-new { background: #00bcd4; color: white; }
    .unread{background-color: #fff3e0; border-left: 4px solid #ff9800;}
    h1{color: #1a1a2e; font-size: 2rem; margin-bottom: 20px;}
    h2{color: #1a1a2e; margin: 30px 0 15px; font-size: 1.5rem; border-left: 4px solid #ff9800; padding-left: 15px;}
    @media (max-width: 768px){
        main{padding: 15px;}
        th, td{padding: 8px 10px; font-size: 0.85rem;}
        .btn{padding: 6px 12px; font-size: 0.75rem;}       
    }
    .admin-nav { margin: 20px 0 30px; display: flex; gap: 15px; flex-wrap: wrap; }
    .admin-nav a { margin-right: 15px; padding: 10px 25px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white; text-decoration: none; border-radius: 25px; transition: all 0.3s ease; font-weight: 500; }
    .admin-nav a:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2);}
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