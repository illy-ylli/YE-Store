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
