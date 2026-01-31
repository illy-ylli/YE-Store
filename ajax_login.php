<?php
session_start();
require_once 'config/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // per testim te adminit me password te koduar
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';
        $_SESSION['email'] = 'admin@ye-store.com';
        
        echo json_encode([
            'success' => true,
            'role' => 'admin'
        ]);
        exit;
    }
    
     // per testim te userit me password te koduar
    if ($username === 'filon' && $password === 'user123') {
        $_SESSION['user_id'] = 2;
        $_SESSION['username'] = 'filon';
        $_SESSION['role'] = 'user';
        $_SESSION['email'] = 'filoni67@example.com';
        
        echo json_encode([
            'success' => true,
            'role' => 'user'
        ]);
        exit;
    }
   