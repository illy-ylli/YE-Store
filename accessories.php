<?php
session_start();
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// merri produktet Accessories (category_id = 3)
$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = 3 ORDER BY created_at DESC");
$stmt->execute();
$accessoriesProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessories - Y/E Store</title>
    <link rel="stylesheet" href="frontpage.css">
    <style>
        .products-count {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 1.1rem;
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

    <main>
        <section class="products-section">
            <h2>Accessories</h2>
            <div class="products-count">
                <?= count($accessoriesProducts) ?> product(s) found
            </div>
            <div class="product-row">
                <?php if (count($accessoriesProducts) > 0): ?>
                    <?php foreach ($accessoriesProducts as $product): ?>
                        <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
                            <div class="product-card">
                                <div class="product-image">
                                    <?php if($product['image_path'] && file_exists('images/' . $product['image_path'])): ?>
                                        <img src="images/<?= htmlspecialchars($product['image_path']) ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>">
                                    <?php else: ?>
                                        <img src="images/default.png" alt="No Image Available">
                                    <?php endif; ?>
                                </div>
                                <div class="product-footer">
                                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                                    <p>$<?= number_format($product['price'], 2) ?></p>
                                    <?php if($product['is_top_product']): ?>
                                        <span style="color: #ff9900; font-size: 0.8em;">★ Top Product</span>
                                    <?php endif; ?>
                                    <?php if($product['is_new_arrival']): ?>
                                        <span style="color: #0099ff; font-size: 0.8em;">🆕 New Arrival</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="product-card placeholder">
                        <div class="product-image">
                            <img src="images/nothinghere.png" alt="No Accessories Products">
                        </div>
                        <div class="product-footer">
                            <h3>No Accessories Products Available</h3>
                            <p>Check back soon or browse other categories</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
</main>
<footer class="footer">
    <div class="footer-content">
        <p>Contact us: support@ye-store.com | +383 49 123 456</p>
        <p>Follow us on social media</p>
        <p>© 2025 Y/E Store — All rights reserved.</p>
    </div>
</footer>

</body>
</html>