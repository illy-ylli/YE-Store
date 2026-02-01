<?php
session_start();
require_once 'config/Database.php';

// kqyr nese user osht admin edhe i kyqun si admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// lidhu me databaz
$db = new Database();
$conn = $db->getConnection();

// meri statistikat
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];
$totalMessages = $conn->query("SELECT COUNT(*) as count FROM support_messages")->fetch()['count'];
$unreadMessages = $conn->query("SELECT COUNT(*) as count FROM support_messages WHERE is_read = 0")->fetch()['count'];

// meri produktet me id te krijuesit
$products = $conn->query("
    SELECT p.id, p.name, p.price, p.image_path, u.username AS created_by
    FROM products p
    LEFT JOIN users u ON p.created_by = u.id
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// meri support mesazhet
$supportMessages = $conn->query("
    SELECT * FROM support_messages ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// shkruje mesazhin si te lexum nese kerkohet
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
    body { font-family: Arial, sans-serif; }
    h2 { margin-top: 30px; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #222; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .stats-container { display: flex; gap: 20px; margin: 20px 0; }
    .stat-box { 
        background: white; 
        padding: 20px; 
        border-radius: 8px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        flex: 1;
        text-align: center;
    }
    .stat-box h3 { margin: 0; color: #666; }
    .stat-box .number { font-size: 2em; font-weight: bold; margin: 10px 0; }
    .unread { background-color: #fff3cd !important; }
    .action-btn { 
        padding: 5px 10px; 
        margin: 2px; 
        border: none; 
        border-radius: 4px; 
        cursor: pointer; 
        font-size: 0.9em;
    }
    .read-btn { background: #28a745; color: white; }
    .delete-btn { background: #dc3545; color: white; }
    .admin-nav { margin: 20px 0; }
    .admin-nav a { 
        margin-right: 15px; 
        padding: 8px 15px; 
        background: #222; 
        color: white; 
        text-decoration: none;
        border-radius: 4px;
    }
</style>
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="frontpage.php"><img src="images/Logo.png" class="logo-icon" alt="Logo"></a>
    </div>
    <nav>
        <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> (Admin)</span> |
        <a href="frontpage.php">View Store</a> |
        <a href="profile.php">Profile</a> |
        <a href="index.php">Logout</a>
    </nav>
</header>

<main style="padding: 20px;">
    <h1>Admin Dashboard</h1>
    
    <div class="admin-nav">
        <a href="admin_add_product.php">Add New Product</a>
        <a href="#products">Products</a>
        <a href="#messages">Support Messages</a>
    </div>
    
    <!-- Statistikat -->
    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Products</h3>
            <div class="number"><?= $totalProducts ?></div>
        </div>
        <div class="stat-box">
            <h3>Total Users</h3>
            <div class="number"><?= $totalUsers ?></div>
        </div>
        <div class="stat-box">
            <h3>Support Messages</h3>
            <div class="number"><?= $totalMessages ?></div>
            <small><?= $unreadMessages ?> unread</small>
        </div>
    </div>
    
    <!-- sektori i produktev -->
    <h2 id="products">Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td><img src="images/<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="width:50px; height:50px; object-fit:contain;"></td>
                <td><?= htmlspecialchars($p['created_by'] ?? 'Unknown') ?></td>
                <td>
                    <button class="action-btn" onclick="editProduct(<?= $p['id'] ?>)">Edit</button>
                    <button class="action-btn delete-btn" onclick="deleteProduct(<?= $p['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- sektori i support mesazhev -->
    <h2 id="messages">Support Messages (<?= $unreadMessages ?> unread)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Status</th>
                <th>Received At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($supportMessages as $m): ?>
            <tr class="<?= $m['is_read'] ? '' : 'unread' ?>">
                <td><?= htmlspecialchars($m['id']) ?></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= nl2br(htmlspecialchars(substr($m['message'], 0, 100) . (strlen($m['message']) > 100 ? '...' : ''))) ?></td>
                <td><?= $m['is_read'] ? 'Read' : 'Unread' ?></td>
                <td><?= htmlspecialchars($m['created_at']) ?></td>
                <td>
                    <?php if(!$m['is_read']): ?>
                    <a href="?mark_read=<?= $m['id'] ?>" class="action-btn read-btn">Mark Read</a>
                    <?php endif; ?>
                    <button class="action-btn delete-btn" onclick="deleteMessage(<?= $m['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<script>
function editProduct(id) {
    if(confirm('Edit product #' + id + '?')) {
        alert('Edit functionality would open here for product #' + id);
    }
}

function deleteProduct(id) {
    if(confirm('Are you sure you want to delete product #' + id + '?')) {
        window.location.href = 'admin_delete_product.php?id=' + id;
    }
}

function deleteMessage(id) {
    if(confirm('Delete message #' + id + '?')) {
        window.location.href = 'admin_delete_message.php?id=' + id;
    }
}
</script>
</body>
</html>