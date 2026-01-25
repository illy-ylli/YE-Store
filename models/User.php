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
    
  