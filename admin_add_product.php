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
    