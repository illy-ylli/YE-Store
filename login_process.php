<?php
session_start(); // MUST be first line, no blank lines above

require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userObj = new User();
    $user = $userObj->login($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['username']= $user['username'];

        // redirect
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: frontpage.php");
        }
        exit;
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid request method.";
}
