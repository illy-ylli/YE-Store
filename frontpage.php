<?php
// nise session edhe lidhu me databaz
session_start();
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// debug moda
error_reporting(E_ALL);
ini_set('display_errors', 1);

// merri gjith produktet e databazes me i bo debug
$allProducts = $conn->query("SELECT id, name, is_top_product, is_new_arrival, image_path FROM products")->fetchAll(PDO::FETCH_ASSOC);

// outputi i debugit
echo '<div style="background: #f8f9fa; padding: 15px; margin: 10px; border-radius: 5px; border: 1px solid #ddd; display: none;">';
echo '<h3>DEBUG: All Products in Database</h3>';
echo '<table border="1" cellpadding="5" style="font-size: 12px;">';
echo '<tr><th>ID</th><th>Name</th><th>Top Product</th><th>New Arrival</th><th>Image</th></tr>';
foreach ($allProducts as $p) {
    echo '<tr>';
    echo '<td>' . $p['id'] . '</td>';
    echo '<td>' . htmlspecialchars($p['name']) . '</td>';
    echo '<td>' . ($p['is_top_product'] ? '✓ YES' : '✗ NO') . '</td>';
    echo '<td>' . ($p['is_new_arrival'] ? '✓ YES' : '✗ NO') . '</td>';
    echo '<td>' . $p['image_path'] . '</td>';
    echo '</tr>';
}
echo '</table>';
echo '</div>';

// qiti top produktet (me is_top_product = 1)
$stmtTop = $conn->prepare("SELECT * FROM products WHERE is_top_product = 1 ORDER BY created_at DESC");
$stmtTop->execute();
$topProducts = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

// qiti produktet e reja (me is_new_arrival = 1)
$stmtNew = $conn->prepare("SELECT * FROM products WHERE is_new_arrival = 1 ORDER BY created_at DESC");
$stmtNew->execute();
$newArrivals = $stmtNew->fetchAll(PDO::FETCH_ASSOC);

// debug: trego numrat
echo '<div style="background: #e9ecef; padding: 10px; margin: 10px; border-radius: 5px; display: none;">';
echo 'Top Products found: ' . count($topProducts) . '<br>';
echo 'New Arrivals found: ' . count($newArrivals) . '<br>';
echo '</div>';
?>
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
    <!-- banneri -->
    <section class="hero">
        <h1>Welcome to Y/E Online Store</h1>
        <p>Discover the latest products at the best prices.</p>
    </section>

    <!-- top produktet -->
<section class="products-section">
    <h2>Top Products <span style="font-size: 1rem; color: #666;"></span></h2>
    <div class="product-row">
        <?php if (count($topProducts) > 0): ?>
            <?php foreach ($topProducts as $product): ?>
                <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
                    <div class="product-card">
                        <div class="product-image">
                            <?php 
                            $imageFile = 'images/' . $product['image_path'];
                            if ($product['image_path'] && file_exists($imageFile)): 
                            ?>
                                <img src="<?= $imageFile ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <img src="images/default.png" alt="No Image Available">
                            <?php endif; ?>
                        </div>
                        <div class="product-footer">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$<?= number_format($product['price'], 2) ?></p>
                            <div class="product-status">
                                <span class="status-badge top-badge">★ Top Product</span>
                                <?php if($product['is_new_arrival']): ?>
                                    <span class="status-badge new-badge">🆕 New Arrival</span>
                                <?php endif; ?>
                            </div>
                            <small style="color: #666; font-size: 0.8rem; margin-top: 5px; display: block;">
                                Product ID: <?= $product['id'] ?>
                            </small>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- shkruaj qe nuk ka top produkte nese ska hiq -->
            <div class="product-card placeholder">
                <div class="product-image">
                    <img src="images/nothinghere.png" alt="No Products Available">
                </div>
                <div class="product-footer">
                    <h3>No Top Products in Database</h3>
                    <p>Add products via Admin Panel and check "Top Product"</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- produktet e reja (prej databaze) -->
<section class="products-section">
    <h2>New Arrivals <span style="font-size: 1rem; color: #666;"></span></h2>
    <div class="product-row">
        <?php if (count($newArrivals) > 0): ?>
            <?php foreach ($newArrivals as $product): ?>
                <a href="product.php?id=<?= $product['id'] ?>" class="product-link">
                    <div class="product-card">
                        <div class="product-image">
                            <?php 
                            $imageFile = 'images/' . $product['image_path'];
                            if ($product['image_path'] && file_exists($imageFile)): 
                            ?>
                                <img src="<?= $imageFile ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <img src="images/default.png" alt="No Image Available">
                            <?php endif; ?>
                        </div>
                        <div class="product-footer">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$<?= number_format($product['price'], 2) ?></p>
                            <div class="product-status">
                                <?php if($product['is_top_product']): ?>
                                    <span class="status-badge top-badge">★ Top Product</span>
                                <?php endif; ?>
                                <span class="status-badge new-badge">🆕 New Arrival</span>
                            </div>
                            <small style="color: #666; font-size: 0.8rem; margin-top: 5px; display: block;">
                                Product ID: <?= $product['id'] ?>
                            </small>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- mesazhi nese nuk ka asnje produkt te ri -->
            <div class="product-card placeholder">
                <div class="product-image">
                    <img src="images/nothinghere.png" alt="No Products Available">
                </div>
                <div class="product-footer">
                    <h3>No New Arrivals in Database</h3>
                    <p>Add products via Admin Panel and check "New Arrival"</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

    </main>
</div>
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