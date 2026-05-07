<?php
// profile.php
session_start();
require_once 'config/Database.php';

// Kontrollo nese perdoruesi eshte i kycur
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Merr te dhenat e perdoruesit nga database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Per te dhenat e profilit (nese nuk ekzistojne ne database)
$fullName = $user['full_name'] ?? '';
$country = $user['country'] ?? 'Kosovo';
$profilePicture = $user['profile_picture'] ?? 'default-profile.png';

// Merr metodat e pageses se perdoruesit
$stmt = $conn->prepare("SELECT * FROM payment_methods WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Shtimi i metodes se re te pageses
$paymentSuccess = $paymentError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $cardName = $_POST['card_name'] ?? '';
    $cardNumber = $_POST['card_number'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    
    // Validimi i thjeshte
    if (empty($cardName) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        $paymentError = "Ju lutemi plotesoni te gjitha fushat.";
    } else {
        // Ruaje ne database
        $stmt = $conn->prepare("
            INSERT INTO payment_methods (user_id, card_name, card_number, expiry_date, cvv, created_at) 
            VALUES (:user_id, :card_name, :card_number, :expiry_date, :cvv, NOW())
        ");
        
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':card_name' => $cardName,
            ':card_number' => $cardNumber,
            ':expiry_date' => $expiryDate,
            ':cvv' => $cvv
        ]);
        
        $paymentSuccess = "Metoda e pageses u shtua me sukses!";
        
        // Rifresko metodat e pageses
        $stmt = $conn->prepare("SELECT * FROM payment_methods WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Fshirja e metodes se pageses
if (isset($_GET['delete_payment']) && is_numeric($_GET['delete_payment'])) {
    $paymentId = (int)$_GET['delete_payment'];
    $stmt = $conn->prepare("DELETE FROM payment_methods WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $paymentId, ':user_id' => $_SESSION['user_id']]);
    header("Location: profile.php");
    exit;
}
// Perditesimi i profilit
$profileSuccess = $profileError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullName = $_POST['full_name'] ?? '';
    $country = $_POST['country'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // Perditeso te dhenat
    $stmt = $conn->prepare("
        UPDATE users SET full_name = :full_name, country = :country, email = :email WHERE id = :id
    ");
    
    if ($stmt->execute([
        ':full_name' => $fullName,
        ':country' => $country,
        ':email' => $email,
        ':id' => $_SESSION['user_id']
    ])) {
        $profileSuccess = "Te dhenat u perditesuan me sukses!";
        $_SESSION['email'] = $email;
        
        // Rifresko te dhenat e perdoruesit
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $fullName = $user['full_name'] ?? '';
        $country = $user['country'] ?? 'Kosovo';
    } else {
        $profileError = "Gabim gjate perditesimit te te dhenave.";
    }
}

// Ndryshimi i fjalekalimit
$passwordSuccess = $passwordError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Verifiko fjalekalimin aktual
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (password_verify($currentPassword, $userData['password'])) {
        if ($newPassword === $confirmPassword) {
            if (strlen($newPassword) >= 6) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->execute([':password' => $hashedPassword, ':id' => $_SESSION['user_id']]);
                $passwordSuccess = "Fjalekalimi u ndryshua me sukses!";
            } else {
                $passwordError = "Fjalekalimi i ri duhet te kete te pakten 6 karaktere.";
            }
        } else {
            $passwordError = "Fjalekalimi i ri dhe konfirmimi nuk perputhen.";
        }
    } else {
        $passwordError = "Fjalekalimi aktual eshte i pasakte.";
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profili im - Y/E Store</title>
<link rel="stylesheet" href="frontpage.css">
<link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="page-content">
    <!-- headeri -->
    <header class="header">
        <div class="logo">
            <a href="frontpage.php">
                <img src="images/Logo.png" class="logo-icon" alt="Logo">
            </a>
        </div>

        <nav class="navbar">
            <ul class="nav-links">
                <li class="dropdown"><a href="frontpage.php">Home</a></li>

                <li class="dropdown"><a href="#">Kategoritë</a>
                    <ul class="dropdown-menu">
                        <li><a href="electronics.php">Elektronikë</a></li>
                        <li><a href="homekitchen.php">Shtëpiake & Kuzhinë</a></li>
                        <li><a href="accessories.php">Aksesorë</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href="#">Kontakt</a>
                    <ul class="dropdown-menu">
                        <li><a href="aboutus.php">Rreth nesh</a></li>
                        <li><a href="support.php">Mbështetje</a></li>
                    </ul>
                </li>

                <li class="dropdown profile-dropdown"><a href="profile.php">Profili</a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">Llogaria ime</a></li>
                        <li><a href="index.php">Dilni</a></li>
                        
                    </ul>
                </li>
            </ul>

            <div class="header-icons">
            <a href="cart.php">
            <img src="images/cart.png" class="cart-icon" alt="Cart">
            </a>
            </div>
        </nav>
    </header>
     <main>

        </body>
</html>