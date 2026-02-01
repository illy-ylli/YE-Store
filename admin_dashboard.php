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
