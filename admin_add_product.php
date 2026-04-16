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
        
        if (in_array($fileExt, $allowed)) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $image_path = $uniqueName;
            } else {
                $error = "Gabim gjatë ngarkimit të fotografisë.";
            }
        } else {
            $error = "Lejohen vetëm fotografi JPG, PNG, GIF.";
        }
    } else {
        $error = "Ju lutemi zgjidhni një fotografi për produktin.";
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
            
             $success = "Produkti u shtua me sukses! Do të shfaqet në faqen kryesore.";
            $_POST = array(); // Pastro formën
        } catch (PDOException $e) {
            $error = "Gabim në database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shto Produkt - Paneli Administratorit</title>
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
           transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid rgba(0,0,0,0.05); 
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
        
        .btn { 
    padding: 12px 0px; 
    border: none; 
    border-radius: 10px; 
    cursor: pointer; 
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: block; /* Shtoni këtë */
    width: 100%; /* Sigurohuni që është 100% */
}

.btn-primary { 
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white; 
    width: 100%;
    margin-bottom: 15px; 
}
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76,175,80,0.3);
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
        
        @media (max-width: 768px) {
            .form-container {
                margin: 20px;
                padding: 20px;
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
            <h2>Shto Produkt të Ri</h2>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Emri i Produktit <span class="required-star">*</span></label>
                    <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Shembull: Laptop Asus ROG" required>
                </div>
                
                <div class="form-group">
                    <label>Përshkrimi</label>
                    <textarea name="description" placeholder="Përshkruani produktin në detaje..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Fotografia e Produktit <span class="required-star">*</span></label>
                    <input type="file" name="product_image" accept="image/*" required>
                    <small style="color: #666; display: block; margin-top: 5px;">Lejohen: JPG, JPEG, PNG, GIF</small>
                </div>
                
                <div class="form-group">
                    <label>Shfaq në Faqen Kryesore</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_top_product" value="1" checked>
                            <label>Top Produkt</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="is_new_arrival" value="1" checked>
                            <label>Ardhje e Re</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Shto Produktin</button>
                <a href="admin_dashboard.php" class="btn btn-secondary">Kthehu te Paneli</a>
            </form>
        </div>
    </main>
</body>
</html>