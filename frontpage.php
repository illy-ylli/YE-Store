<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Y/E Store</title>
    <link rel="stylesheet" href="frontpage.css">
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

               <li class="dropdown profile-dropdown">
    <span class="profile-btn">Profile</span>
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

    <!-- HERO BANNER -->
    <section class="hero">
        <h1>Welcome to Y/E Online Store</h1>
        <p>Discover the latest products at the best prices.</p>
    </section>

    <!-- TOP PRODUCTS -->
<section class="products-section">
    <h2>Top Products</h2>
    <div class="product-row">
        <div class="product-card">
            <a href="product.php?id=1">
                <div class="product-image">
                    <img src="images/mouse.png" alt="Logitech G PRO X Superlight 2">
                </div>
                <div class="product-footer">
                    <h3>Logitech G PRO X Superlight 2</h3>
                    <p>$79.99</p>
                </div>
            </a>
        </div>

        <div class="product-card">
            <a href="product.php?id=2">
                <div class="product-image">
                    <img src="images/laptopbag.png" alt="Lenovo Laptop Bag T210 Fabric">
                </div>
                <div class="product-footer">
                    <h3>Lenovo Laptop Bag T210 Fabric</h3>
                    <p>$15.99</p>
                </div>
            </a>
        </div>

        <div class="product-card">
            <a href="product.php?id=3">
                <div class="product-image">
                    <img src="images/amazon.png" alt="Amazon Fire TV Stick 4K Plus">
                </div>
                <div class="product-footer">
                    <h3>Amazon Fire TV Stick 4K Plus</h3>
                    <p>$29.99</p>
                </div>
            </a>
        </div>

        <div class="product-card placeholder">
            <div class="product-image">
                <img src="images/nothinghere.png" alt="Nothing Here">
            </div>
        </div>
    </div>
</section>

<!-- NEW ARRIVALS -->
<section class="products-section">
    <h2>New Arrivals</h2>
    <div class="product-row">
        <div class="product-card">
            <a href="product.php?id=4">
                <div class="product-image">
                    <img src="images/airfrier.png" alt="COSORI Air Fryer Pro">
                </div>
                <div class="product-footer">
                    <h3>COSORI Air Fryer Pro</h3>
                    <p>$89.99</p>
                </div>
            </a>
        </div>

        <div class="product-card">
            <a href="product.php?id=5">
                <div class="product-image">
                    <img src="images/monitor.png" alt="UMax 22 Touch - 22 Inch Portable Monitor">
                </div>
                <div class="product-footer">
                    <h3>UMax 22 Touch - 22 Inch Portable Monitor</h3>
                    <p>$263.99</p>
                </div>
            </a>
        </div>

        <div class="product-card">
            <a href="product.php?id=6">
                <div class="product-image">
                    <img src="images/ereader.png" alt="Amazon Kindle Paperwhite">
                </div>
                <div class="product-footer">
                    <h3>Amazon Kindle Paperwhite</h3>
                    <p>$189.99</p>
                </div>
            </a>
        </div>

        <div class="product-card">
            <a href="product.php?id=7">
                <div class="product-image">
                    <img src="images/glasses.png" alt="Ray-Ban Meta (Gen 1)">
                </div>
                <div class="product-footer">
                    <h3>Ray-Ban Meta (Gen 1)</h3>
                    <p>$224.99</p>
                </div>
            </a>
        </div>
    </div>
</section>


    </main>
</div>
<script src="clickable-products.js"></script>
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
