<?php
session_start();
require_once 'config/Database.php';

// kqyr nese useri osht admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
//kto tregojn nese useri mer error apo sukses
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //merr data prej post kerkeses
    $name = $_POST['name'] ?? ''; //emri produktit
    $description = $_POST['description'] ?? ''; //pershkrimi i tij
    $price = $_POST['price'] ?? ''; //qmimi
    $category_id = $_POST['category_id'] ?? ''; //kategoria qe i perket (elektronik, home kitchen apo accessories)
    $is_top_product = isset($_POST['is_top_product']) ? 1 : 0; //a osht produkti top product? 1 po, 0 jo
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0; // a osht produkti new ? 1 po, 0 jo
    
    // file upload
    $image_path = 'default.png';
    //kqyr nese osht ngarku mir fotoja
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/'; //follderi ku ruhen fotot
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true); //krijo nje directory nese nuk ekziston
        
        $fileExt = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION)); //shkruje file ext (png, jpeg)
        
        //krijo nje emer unik ne menyr mos me pas overwriting
        $uniqueName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadFile = $uploadDir . $uniqueName;
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowed)) { //kqyr a lejohet file

        //nese lejohet livrite prej venit te perkohshem ne permanent
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $image_path = $uniqueName;
            } else {
                $error = "Error uploading image.";
            }
        } else {
            $error = "Only JPG, PNG, GIF files allowed.";
        }
    }
    // RUAJTJA NE DATABAZ
     
    if (empty($error)) {   //lejon te vazhdoj vetem nese ska pas error ma heret
        $db = new Database();
        $conn = $db->getConnection();
        
        //pergatite sql per me shtu vlerat
        $stmt = $conn->prepare("
            INSERT INTO products (name, description, price, category_id, image_path, 
                                 created_by, is_top_product, is_new_arrival) 
            VALUES (:name, :description, :price, :category_id, :image_path, 
                    :user_id, :top, :new)
        ");
        
        try {  //ekzekuto querin me vlera qe jon plots
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':category_id' => $category_id,
                ':image_path' => $image_path,
                ':user_id' => $_SESSION['user_id'],
                ':top' => $is_top_product,
                ':new' => $is_new_arrival
            ]);
            
            $success = "Product added successfully! Will appear on homepage.";
            $_POST = array(); //fshij formen pasi esht perdorur me sukses (puna qe me perdor apet)
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
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="frontpage.css">
    <style>
        /*STILI I FFORMES osht shkru puna qe me rujt hapsir*/
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
        .btn { padding: 10px 1px; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn-primary { background: #222; color: white; }
        .btn-secondary { background: #6c757d; color: white; text-decoration: none; display: inline-block; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="images/Logo.png" style="height:120px;" alt="Logo"></a>
        </div>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a> |
            <a href="index.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2 style="text-align:center;">Add New Product</h2>
            
            <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Price *</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        <option value="1" <?= ($_POST['category_id'] ?? '') == '1' ? 'selected' : '' ?>>Electronics</option>
                        <option value="2" <?= ($_POST['category_id'] ?? '') == '2' ? 'selected' : '' ?>>Home & Kitchen</option>
                        <option value="3" <?= ($_POST['category_id'] ?? '') == '3' ? 'selected' : '' ?>>Accessories</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Product Image *</label>
                    <input type="file" name="product_image" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label>Show on Homepage</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_top_product" value="1" checked>
                            <label>Top Product</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_new_arrival" value="1" checked>
                            <label>New Arrival</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:100%;">Add Product</button>
                <a href="admin_dashboard.php" class="btn btn-secondary" style="width:100%;margin-top:10px;text-align:center;">Back to Dashboard</a>
            </form>
        </div>
    </main>
</body>
</html>