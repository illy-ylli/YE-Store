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

// Per te dhenat e profilit
$fullName = $user['full_name'] ?? '';
$country = $user['country'] ?? 'Kosovo';
$profilePicture = $user['profile_picture'] ?? 'default-profile.png';

// Merr metodat e pageses
$stmt = $conn->prepare("SELECT * FROM payment_methods WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ngarkimi i fotografise se profilit
$photoSuccess = $photoError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo'])) {
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/profiles/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $fileExt = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowed)) {
            $uniqueName = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExt;
            $uploadFile = $uploadDir . $uniqueName;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
                if ($profilePicture !== 'default-profile.png' && file_exists('images/profiles/' . $profilePicture)) {
                    unlink('images/profiles/' . $profilePicture);
                }
                
                $stmt = $conn->prepare("UPDATE users SET profile_picture = :pic WHERE id = :id");
                $stmt->execute([':pic' => $uniqueName, ':id' => $_SESSION['user_id']]);
                
                $profilePicture = $uniqueName;
                $photoSuccess = "Foto e profilit u ndryshua me sukses!";
            } else {
                $photoError = "Gabim gjate ngarkimit te fotografise.";
            }
        } else {
            $photoError = "Lejohen vetem fotografi JPG, JPEG, PNG, GIF.";
        }
    } else {
        $photoError = "Ju lutemi zgjidhni nje fotografi.";
    }
}

// Shtimi i metodes se re te pageses
$paymentSuccess = $paymentError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $cardName = $_POST['card_name'] ?? '';
    $cardNumber = $_POST['card_number'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    
    if (empty($cardName) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        $paymentError = "Ju lutemi plotesoni te gjitha fushat.";
    } else {
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

<main class="profile-main">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-image-container">
            <img src="images/profiles/<?= htmlspecialchars($profilePicture) ?>" 
                 alt="Foto e profilit" 
                 class="profile-image"
                 onerror="this.src='images/default-profile.png'">
            <button class="edit-photo-btn" onclick="document.getElementById('photoModal').style.display='flex'">📷</button>
        </div>
        <div>
            <h1><?= htmlspecialchars($user['username']) ?></h1>
            <p>Anëtar që nga: <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></p>
        </div>
    </div>

    <!-- te dhenat personale sektor -->
    <div class="profile-section">
        <h2>Të Dhënat Personale</h2>
        
        <?php if($profileSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($profileSuccess) ?></div>
        <?php endif; ?>
        <?php if($profileError): ?>
            <div class="alert alert-error"><?= htmlspecialchars($profileError) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="row-2cols">
                <div class="form-group">
                    <label>Emri i plotë</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($fullName) ?>" placeholder="Emri juaj i plotë">
                </div>
                <div class="form-group">
                    <label>Emri i përdoruesit</label>
                    <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled style="background: #f5f5f5;">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Shteti</label>
                    <select name="country">
                        <option value="Kosovo" <?= $country == 'Kosovo' ? 'selected' : '' ?>>Kosovo</option>
                        <option value="Albania" <?= $country == 'Albania' ? 'selected' : '' ?>>Albania</option>
                        <option value="Other" <?= $country == 'Other' ? 'selected' : '' ?>>Tjetër</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Ruaj Ndryshimet</button>
        </form>
    </div>

    <!--siguria sektor -->
    <div class="profile-section">
        <h2>Siguria</h2>
        
        <?php if($passwordSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($passwordSuccess) ?></div>
        <?php endif; ?>
        <?php if($passwordError): ?>
            <div class="alert alert-error"><?= htmlspecialchars($passwordError) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="row-2cols">
                <div class="form-group">
                    <label>Fjalëkalimi aktual</label>
                    <input type="password" name="current_password" placeholder="Fjalëkalimi aktual" required>
                </div>
                <div class="form-group">
                    <label>Fjalëkalimi i ri</label>
                    <input type="password" name="new_password" placeholder="Fjalëkalimi i ri" required>
                </div>
                <div class="form-group">
                    <label>Konfirmo fjalëkalimin e ri</label>
                    <input type="password" name="confirm_password" placeholder="Konfirmo fjalëkalimin" required>
                </div>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Ndrysho Fjalëkalimin</button>
        </form>
    </div>

    <!--  metodat e pageses seksion-->
    <div class="profile-section">
        <h2>Metodat e Pagesës</h2>
        
        <?php if($paymentSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($paymentSuccess) ?></div>
        <?php endif; ?>
        <?php if($paymentError): ?>
            <div class="alert alert-error"><?= htmlspecialchars($paymentError) ?></div>
        <?php endif; ?>
        
        <?php if(count($paymentMethods) > 0): ?>
            <div style="margin-bottom: 25px;">
                <h3 style="margin-bottom: 15px; color: #666;">Kartat e ruajtura:</h3>
                <?php foreach($paymentMethods as $card): ?>
                    <div class="payment-card">
                        <div class="payment-info">
                            <div class="card-icon">💳</div>
                            <div class="payment-details">
                                <strong><?= htmlspecialchars($card['card_name']) ?></strong>
                                <span class="card-number-masked">•••• •••• •••• <?= substr($card['card_number'], -4) ?></span>
                                <small>Skadon: <?= htmlspecialchars($card['expiry_date']) ?></small>
                            </div>
                        </div>
                        <button class="btn btn-danger" onclick="if(confirm('A jeni i sigurt që dëshironi të fshini këtë kartë?')) location.href='profile.php?delete_payment=<?= $card['id'] ?>'">🗑️ Fshije</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert" style="background: #f0f0f0; color: #666; text-align: center;">
                Nuk keni asnjë kartë të ruajtur.
            </div>
        <?php endif; ?>
        
        <button class="btn btn-success" onclick="document.getElementById('paymentModal').style.display='flex'" style="width: auto;">Shto Kartë të Re</button>
    </div>
</main>

<!-- Pagesa Modal -->
<div id="paymentModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" onclick="document.getElementById('paymentModal').style.display='none'">&times;</span>
        <h3>Shto Kartë të Re</h3>
        <form method="POST">
            <div class="form-group">
                <label>Emri në kartë</label>
                <input type="text" name="card_name" required>
            </div>
            <div class="form-group">
                <label>Numri i kartës</label>
                <input type="text" name="card_number" maxlength="19" required>
            </div>
            <div class="row-2cols">
                <div class="form-group">
                    <label>Data e skadimit (MM/YY)</label>
                    <input type="text" name="expiry_date" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="password" name="cvv" maxlength="4" required>
                </div>
            </div>
            <button type="submit" name="add_payment" class="btn btn-primary" style="width: 100%;">Ruaj Kartën</button>
        </form>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <span class="close-modal" onclick="document.getElementById('photoModal').style.display='none'">&times;</span>
        <h3>📷 Ndrysho Foton e Profilit</h3>
        
        <?php if($photoSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($photoSuccess) ?></div>
        <?php endif; ?>
        <?php if($photoError): ?>
            <div class="alert alert-error"><?= htmlspecialchars($photoError) ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Zgjidh një foto</label>
                <input type="file" name="profile_photo" accept="image/*" required>
                <small style="color: #666; display: block; margin-top: 8px;">Lejohen: JPG, JPEG, PNG, GIF</small>
            </div>
            <button type="submit" name="upload_photo" class="btn btn-primary" style="width: 100%;">📤 Ngarko Foton</button>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="footer-content">
        <p>Na kontaktoni: support@ye-store.com | +383 49 123 456</p>
        <p>Na ndiqni në rrjetet sociale</p>
        <p>© 2025 Y/E Store — Të gjitha të drejtat e rezervuara.</p>
    </div>
</footer>

<script>
    window.onclick = function(event) {
        var paymentModal = document.getElementById('paymentModal');
        var photoModal = document.getElementById('photoModal');
        if (event.target == paymentModal) {
            paymentModal.style.display = 'none';
        }
        if (event.target == photoModal) {
            photoModal.style.display = 'none';
        }
    }
    
    document.querySelector('input[name="card_number"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formatted += ' ';
            formatted += value[i];
        }
        e.target.value = formatted;
    });
    
    document.querySelector('input[name="expiry_date"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
</script>

</body>
</html>