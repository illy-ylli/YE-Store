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
    // nis session t userit pasi te behet login
    public static function loginUser($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['full_name'] = $userData['full_name'] ?? '';
    }
    
    // beje logout userin
    public static function logout() {
        session_destroy();
    }