<?php
require_once 'config/Database.php';

class User {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getConnection();
    }
    
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username OR email = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function register($data) {
        // kontrrollo nese useri ekziston
        $sql = "SELECT id FROM users WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email']
        ]);
        
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }
                // passwordi ne hashtag
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        // Inserto user ne databaz
        $sql = "INSERT INTO users (username, email, password, full_name, country, role) 
                VALUES (:username, :email, :password, :full_name, :country, 'user')";
        
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $hashedPassword,
            ':full_name' => $data['full_name'] ?? '',
            ':country' => $data['country'] ?? 'Kosovo'
        ]);
        
        return [
            'success' => $success,
            'message' => $success ? 'Registration successful' : 'Registration failed'
        ];
    }
}
?>
