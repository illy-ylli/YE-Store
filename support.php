<?php
require_once 'config/Database.php';

// 3.3: handle form submission
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $db = new Database();$conn = $db->getConnection();

    $stmt = $conn->prepare("
        INSERT INTO support_messages (name, email, message) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$name, $email, $message]);

    if ($stmt->rowCount() > 0) {
        $successMessage = "Message sent successfully!";
    } else {
        $successMessage = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support - Y/E Store</title>
    <link rel="stylesheet" href="frontpage.css">
    <style>
        /* Duke vendos sektorin/boxat e supportit ne midis*/
        .support-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
            padding: 50px 20px;
            flex-wrap: wrap;
        }

        .support-section img {
            max-width: 300px;
            width: 100%;
            height: auto;
        }

        .support-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 400px;
            width: 100%;
        }

        .support-form input,
        .support-form textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid gray;
            font-family: "Segoe UI", Arial, sans-serif;
            width: 100%;
            box-sizing: border-box;
        }

        .support-form button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #222;
            color: white;
            cursor: pointer;
            font-size: 1rem;
        }

        .support-form button:hover {
            background-color: #444;
        }

        .success-msg {
            color: green;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<main>
<div class="page-content">
    <!-- HEADER -->
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

    <!-- SUPPORT SECTION -->
    <main class="support-section">
        <img src="images/support.png" alt="Customer Support">
        <form class="support-form" method="POST" action="support.php">
            <?php if($successMessage): ?>
                <div class="success-msg"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Write your message..." rows="6" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </main>
</div>
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-content">
        <p>Contact us: support@ye-store.com | +383 49 123 456</p>
        <p>Follow us on social media</p>
        <p>© 2025 Y/E Store — All rights reserved.</p>
    </div>
</footer>
</body>
</html>
