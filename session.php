<?php
session_start();

class SessionManager {
    
    // kqyr nese user u bo log in
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // kqyr nese user osht admin
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    