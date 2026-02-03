<?php
session_start();
require_once 'config/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}   //nese useri osht user normal dhe nuk e ka rolin admin me dergu tek index.php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit; //mere id e produktit nese id e produktit nuk o valide kthe personin/adminin tek admin dashboard
}

$productId = (int)$_GET['id']; //kthe ne integer sa per siguri

//bone lidhjen me databaz
$db = new Database();
$conn = $db->getConnection();

//merr infot e produktit nese ka nevoj me mush vet formen
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) die("Product not found."); //nese produkti nuk ekziston shfaqet mesazhi

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //merr data prej post kerkeses
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $is_top_product = isset($_POST['is_top_product']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    
    $image_path = $product['image_path'];
    
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $fileExt = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
        $uniqueName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadFile = $uploadDir . $uniqueName;
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowed)) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                if ($image_path !== 'default.png' && file_exists($uploadDir . $image_path)) {
                    unlink($uploadDir . $image_path);
                }
                $image_path = $uniqueName;
            } else {
                $error = "Error uploading image.";
            }
        } else {
            $error = "Only JPG, PNG, GIF files allowed.";
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("
            UPDATE products SET name = :name, description = :description, price = :price, 
            category_id = :category_id, image_path = :image_path, is_top_product = :top, 
            is_new_arrival = :new, updated_at = NOW() WHERE id = :id
        ");
        
        try {
            $stmt->execute([
                ':name' => $name, ':description' => $description, ':price' => $price,
                ':category_id' => $category_id, ':image_path' => $image_path,
                ':top' => $is_top_product, ':new' => $is_new_arrival, ':id' => $productId
            ]);
            
            $success = "Product updated successfully!";
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute([':id' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="frontpage.css">
    <style>
        .admin-header { background: white; height: 80px; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-header nav { display: flex; align-items: center; gap: 15px; color: #666; }
        .admin-header nav a { color: #666; text-decoration: none; padding: 8px 15px; border-radius: 4px; }
        .admin-header nav a:hover { background-color: #f0f0f0; color: #222; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 30px auto; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .checkbox-group { display: flex; gap: 20px; margin-top: 10px; }
        .checkbox-item { display: flex; align-items: center; gap: 5px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn-primary { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; text-decoration: none; display: inline-block; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .current-image { max-width: 200px; height: auto; border-radius: 4px; border: 1px solid #ddd; margin: 10px 0; }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="images/Logo.png" style="height:120px;" alt="Logo"></a>
        </div>
        <nav>
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> (Admin)</span> |
            <a href="index.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2 style="text-align:center;">Edit Product #<?= $product['id'] ?></h2>
            
            <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Price *</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" required>
                        <option value="1" <?= $product['category_id'] == 1 ? 'selected' : '' ?>>Electronics</option>
                        <option value="2" <?= $product['category_id'] == 2 ? 'selected' : '' ?>>Home & Kitchen</option>
                        <option value="3" <?= $product['category_id'] == 3 ? 'selected' : '' ?>>Accessories</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Current Image</label>
                    <?php if($product['image_path'] && file_exists('images/' . $product['image_path'])): ?>
                        <img src="images/<?= htmlspecialchars($product['image_path']) ?>" class="current-image">
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>
                    
                    <label>New Image (Optional)</label>
                    <input type="file" name="product_image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label>Show on Homepage</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_top_product" value="1" <?= $product['is_top_product'] ? 'checked' : '' ?>>
                            <label>Top Product</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_new_arrival" value="1" <?= $product['is_new_arrival'] ? 'checked' : '' ?>>
                            <label>New Arrival</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Update Product</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary" style="flex:1;text-align:center;">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>