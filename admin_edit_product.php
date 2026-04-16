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

if (!$product) die("Produkti nuk u gjet."); //nese produkti nuk ekziston shfaqet mesazhi

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //merr data prej post kerkeses
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $is_top_product = isset($_POST['is_top_product']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    
    //ruje foton ekzistuse si default
    $image_path = $product['image_path'];
    // me kontrollu nese osht ngarku naj foto e re
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $fileExt = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
        $uniqueName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadFile = $uploadDir . $uniqueName;
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowed)) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                // fshije foton e vjeter nese nuk esht default
                if ($image_path !== 'default.png' && file_exists($uploadDir . $image_path)) {
                    unlink($uploadDir . $image_path);
                }
                $image_path = $uniqueName;
            } else {
                $error = "Gabim gjatë ngarkimit të fotografisë.";
            }
        } else {
            $error = "Lejohen vetëm fotografi JPG, PNG, GIF.";
        }
    }
    
    //perditso produktin ne databaz
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
        
        $success = "Produkti u përditësua me sukses!";
      $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute([':id' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Gabim në database: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>"Editoni Produkt - Paneli Administratorit"</title>
    <link rel="stylesheet" href="frontpage.css">
    <style>
        .admin-header { 
            background: linear-gradient(135deg, #fbfbfb 0%, #ffffff 100%);
            height: 80px; 
            padding: 0 30px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .admin-header nav { 
            display: flex; 
            align-items: center; 
            gap: 20px; 
            color: #fff;
        }
        
        .admin-header nav span {
            padding: 8px 18px;
            border-radius: 25px;
            color: #1d1c1cc4;
            font-weight: 600;
        }
        
        .admin-header nav a { 
            color: #1b1a1a; 
            text-decoration: none; 
            padding: 8px 18px; 
            border-radius: 25px; 
            transition: all 0.3s ease; 
            font-weight: 500;
            display: inline-block; 
        }
        
        .admin-header nav a:hover { 
            background-color: rgba(0,0,0,0.05);
            transform: translateY(-3px); 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .form-container { 
            background: white; 
            padding: 35px; 
            border-radius: 20px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.88);
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
            border: 1px solid rgba(0,0,0,0.05); 
            max-width: 650px; 
            margin: 40px auto;
        }
        
        .form-container h2 {
            text-align: center;
            color: #1a1a2e;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        
        .form-group { 
            margin-bottom: 25px; 
        }
        
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600;
            color: #333;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #e0e0e0; 
            border-radius: 10px; 
            box-sizing: border-box;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: #ff9800;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,152,0,0.1);
        }
        
        .form-group textarea { 
            min-height: 100px; 
            resize: vertical;
        }
        
        .checkbox-group { 
            display: flex; 
            gap: 25px; 
            margin-top: 10px;
        }
        
        .checkbox-item { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        
        .checkbox-item input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .current-image {
            max-width: 150px;
            max-height: 150px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            margin-top: 10px;
            display: block;
        }
        
        .image-preview {
            margin-top: 10px;
        }
        
        .image-preview p {
            margin: 5px 0;
            font-size: 0.85rem;
            color: #666;
        }
        
        .btn { 
            padding: 12px 0px; 
            border: none; 
            border-radius: 10px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white; 
            width: 100%;
            margin-bottom: 15px; 
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33,150,243,0.3);
        }
        
        .btn-secondary { 
            background: linear-gradient(135deg, #1a1a2e 0%, #1a1a2e 100%);
            color: white; 
            text-decoration: none; 
            display: inline-block;
            text-align: center;
            width: 100%;
            margin-top: 0;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26,26,46,0.3);
        }
        
        .alert { 
            padding: 12px 15px; 
            border-radius: 10px; 
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb;
        }
        
        .alert-error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb;
        }
        
        .required-star {
            color: #f44336;
        }
        
        .info-text {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
            display: block;
        }
        
        @media (max-width: 768px) {
            .form-container {
                margin: 20px;
                padding: 20px;
            }
            
            .current-image {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="images/Logo.png" style="height:120px;" alt="Logo"></a>
        </div>
        <nav>
             <span>Mirë se vini, <?= htmlspecialchars($_SESSION['username']) ?> (Admin)</span>
            <a href="index.php">Dil</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Editoni Produktin #<?= $product['id'] ?></h2>
            
            <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Emri i Produktit *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Përshkrimi</label>
                    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Çmimi *</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Kategoria <span class="required-star">*</span></label>
                    <select name="category_id" required>
                        <option value="">Zgjidhni Kategorinë</option>
                        <option value="1" <?= $product['category_id'] == 1 ? 'selected' : '' ?>>Elektronikë</option>
                        <option value="2" <?= $product['category_id'] == 2 ? 'selected' : '' ?>>Shtëpi & Kuzhinë</option>
                        <option value="3" <?= $product['category_id'] == 3 ? 'selected' : '' ?>>Aksesorë</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Fotografia Aktuale</label>
                    <?php if($product['image_path'] && file_exists('images/' . $product['image_path'])): ?>
                        <img src="images/<?= htmlspecialchars($product['image_path']) ?>" class="current-image">
                    <?php else: ?>
                        <p>Nuk ka fotografi të ngarkuar për këtë produkt</p>
                    <?php endif; ?>
                    
                    <label>Ndrysho Fotografinë (Opsionale)</label>
                    <input type="file" name="product_image" accept="image/*">
                    <small class="info-text">Lejohen: JPG, JPEG, PNG, GIF. Lëreni thate për të mbajtur foton aktuale.</small>
                </div>
                
                <div class="form-group">
                    <label>Shfaq ne Homepage</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_top_product" value="1" <?= $product['is_top_product'] ? 'checked' : '' ?>>
                            <label>Top Produkt</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_new_arrival" value="1" <?= $product['is_new_arrival'] ? 'checked' : '' ?>>
                            <label>Ardhje e Re</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Përditëso Produktin</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary" style="flex:1;text-align:center;">Kthehu te Paneli</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>