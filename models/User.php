<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $profile_picture;
    public $country;
    public $role;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // regjistrimi i userit te ri
    public function register() {
        $query = "INSERT INTO " . $this->table . " 
                  SET username=:username, email=:email, password=:password, 
                      full_name=:full_name, country=:country";
        
        $stmt = $this->conn->prepare($query);

              // mshefe passwordin me hashtag
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":country", $this->country);
        
        return $stmt->execute();
    }