<?php
session_start();
require_once 'config/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // First check hardcoded users (for admin)
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
    
    // Then check database for other users
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            
            echo json_encode([
                'success' => true,
                'role' => $user['role']
            ]);
            exit;
        }
        
    } catch(Exception $e) {
        // If database fails, still check hardcoded 'filon' user
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
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username or password'
    ]);
}
?>