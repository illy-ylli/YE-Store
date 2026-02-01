<?php
require_once 'config/Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $fullName = $_POST['full_name'] ?? '';
    $country = $_POST['country'] ?? 'Kosovo';
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Username or email already exists'
            ]);
            exit;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert into database
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password, full_name, country, role) 
            VALUES (:username, :email, :password, :full_name, :country, 'user')
        ");
        
        $success = $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':full_name' => $fullName,
            ':country' => $country
        ]);
        
        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Registration successful! You can now log in.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ]);
        }
        
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
?>