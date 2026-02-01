<?php
session_start();
require_once 'config/Database.php';

// kqyr nese useri osht admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// merru me submitin e formularit/forms
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $image_path = $_POST['image_path'] ?? 'default.png';
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // inserto produktin me user_id track
    $stmt = $conn->prepare("
        INSERT INTO products (name, description, price, category_id, image_path, created_by) 
        VALUES (:name, :description, :price, :category_id, :image_path, :user_id)
    ");
    
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':category_id' => $category_id,
        ':image_path' => $image_path,
        ':user_id' => $_SESSION['user_id']  // bone track(gjeje) kush e ka bo add
    ]);
    
    $success = "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="frontpage.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="images/Logo.png" alt="Logo"></a>
        </div>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a> |
            <a href="index.php">Logout</a>
        </nav>
    </header>

    <main style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h2>Add New Product</h2>
        
        <?php if(isset($success)): ?>
            <div style="color: green; margin-bottom: 15px;"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Description" rows="4"></textarea>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <option value="1">Electronics</option>
                <option value="2">Home & Kitchen</option>
                <option value="3">Accessories</option>
            </select>
            <input type="text" name="image_path" placeholder="Image filename (e.g., product.png)" required>
            <button type="submit" style="padding: 10px; background: #222; color: white; border: none; cursor: pointer;">
                Add Product
            </button>
        </form>
    </main>
</body>
</html>