<?php
// nisim sessionin
session_start();

// lidhja me DB
require_once 'config/Database.php';

// 1. Merr ID e produktit nga URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID e produktit nuk eshte valide."); // nese nuk ka ID ose nuk eshte numer
}

$productId = (int)$_GET['id'];

// 2. Lidhja me databazen
$db = new Database();
$conn = $db->getConnection();

// 3. Merr produktin nga DB
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Kontroll nese produkti ekziston
if (!$product) {
    die("Produkti nuk u gjet."); // nese ID nuk egziston
}

// 5. Vendos default nese fusha eshte bosh
$product['image'] = $product['image'] ?? 'images/default-product.png';
$product['description'] = $product['description'] ?? 'Nuk ka pershkrim.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['name']); ?> - Y/E Store</title>
<link rel="stylesheet" href="frontpage.css">
<link rel="stylesheet" href="product.css">
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="logo">
        <a href="frontpage.php">
            <img src="images/Logo.png" class="logo-icon" alt="Logo">
        </a>
    </div>
</header>

<!-- DETAJET E PRODUKTIT -->
<div class="product-detail">
    <!-- FOTO E PRODUKTIT -->
    <img src="images/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

    <!-- EMRI DHE ÇMIMI -->
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <p>$<?php echo number_format($product['price'], 2); ?></p>

    <!-- BUTONI ADD TO CART NUK SHFAQET NUK NUK JENI LOGUAR -->
    <?php if(isset($_SESSION['user_id'])): ?>
        <form method="POST" action="cart_process.php">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" class="add-cart-btn">Add to Cart</button>
        </form>
    <?php else: ?>
        <p>Ju lutem <a href="index.php">log in</a> per te shtuar ne cart.</p>
    <?php endif; ?>

    <!-- PERSHKRIMI -->
    <div class="product-description">
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    </div>
</div>

</body>
</html>
