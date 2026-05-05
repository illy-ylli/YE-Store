<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shporta e blerjeve - Y/E Store</title>
    <link rel="stylesheet" href="frontpage.css">
     <style>
        
        .empty-cart {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh; 
            flex-direction: column;
            gap: 20px;
        }
        .empty-cart img {
            max-width: 300px;
            width: 100%;
            height: auto;
        }
        .empty-cart p {
            font-size: 1.2rem;
            color: #555;
        }
    </style>
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

    <main class="empty-cart">
        <img src="images/emptycart.png" alt="Empty Cart">
        <p>Shporta juaj është bosh!</p>
    </main>
</div>
</main>
<footer class="footer">
    <div class="footer-content">
        <p>Na kontaktoni: support@ye-store.com | +383 49 123 456</p>
        <p>Na ndiqni në rrjetet sociale</p>
        <p>© 2025 Y/E Store — Të gjitha të drejtat e rezervuara.</p>
    </div>
</footer>

</body>
</html>
