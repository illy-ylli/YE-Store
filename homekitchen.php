<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home & Kitchen - Y/E Store</title>
    <link rel="stylesheet" href="frontpage.css">
</head>
<body>
<main>
<div class="page-content">
     <header class="header">
        <div class="logo">
            <a href="frontpage.php">
                <img src="images/Logo.png" class="logo-icon" alt="Logo">
            </a>
        </div>

        <nav class="navbar">
            <ul class="nav-links">
                <li class="dropdown"><a href="frontpage.php">Home</a></li>

                <li class="dropdown"><a href="#">Categories</a>
                    <ul class="dropdown-menu">
                        <li><a href="electronics.php">Electronics</a></li>
                        <li><a href="homekitchen.php">Home & Kitchen</a></li>
                        <li><a href="accessories.php">Accessories</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href="#">Contact</a>
                    <ul class="dropdown-menu">
                        <li><a href="aboutus.php">About us</a></li>
                        <li><a href="support.php">Support</a></li>
                    </ul>
                </li>

                <li class="dropdown profile-dropdown"><a href="profile.php">Profile</a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Account</a></li>
                        <li><a href="index.php">Logout</a></li>
                        
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
        <section class="products-section">
  <h2>Home & Kitchen</h2>
  <div class="product-row">
    <!-- Produktet e filtrume per Home & Kitchen -->
            <div class="product-card">
            <div class="product-image">
                <img src="images/airfrier.png" alt="Air frier">
            </div>
            <div class="product-footer">
                <h3>COSORI Air Fryer Pro</h3>
                <p>$89.99</p>
            </div>
        </div>
</section>
    </main>
</div>
</main>
<script src="clickable-products.js"></script>
<footer class="footer">
    <div class="footer-content">
        <p>Contact us: support@ye-store.com | +383 49 123 456</p>
        <p>Follow us on social media</p>
        <p>© 2025 Y/E Store — All rights reserved.</p>
    </div>
</footer>

</body>
</html>
