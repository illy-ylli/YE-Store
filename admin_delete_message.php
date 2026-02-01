<?php
session_start();
require_once 'config/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $messageId = (int)$_GET['id'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("DELETE FROM support_messages WHERE id = :id");
    $stmt->execute([':id' => $messageId]);
}

header('Location: admin_dashboard.php');
exit;
?>