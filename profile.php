<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Account - Y/E Store</title>
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

    <!-- permbajtja e profilit -->
    <main>
        <!-- profili me foto -->
        <div class="profile-header">
            <img src="images/foto-profili.png" alt="Profile Picture">
            <h1>My Account</h1>
        </div>

        <!-- info e account -->
        <div class="profile-section">
            <h2>Personal Information</h2>
            <p><strong>Name:</strong> Filon Fisteki</p>
            <p><strong>Email:</strong> filoni67@example.com</p>
            <p><strong>Country:</strong> Kosovo</p>
            <button>Change Account Details</button>
        </div>

        <!-- detaje te passwordit -->
        <div class="profile-section">
            <h2>Security</h2>
            <p>Change your password or reset it if you forgot.</p>
            <button>Reset Password</button>
        </div>

        <!-- metodat e pageses -->
        <div class="profile-section">
            <h2>Payment Methods</h2>
            <p>Add or manage your payment methods.</p>
            <button>Add Payment Method</button>
        </div>
    </main>
</div>

<!-- Footeri -->
<footer class="footer">
    <div class="footer-content">
        <p>Contact us: support@ye-store.com | +383 49 123 456</p>
        <p>Follow us on social media</p>
        <p>© 2025 Y/E Store — All rights reserved.</p>
    </div>
</footer>

</body>
</html>
