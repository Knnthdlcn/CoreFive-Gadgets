<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=eshop", "root", "TempPass12345!");
    
    $stmt = $pdo->query("SELECT product_id, product_name, image_path FROM products WHERE product_id = 1");
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Product ID: " . $row['product_id'] . "\n";
    echo "Product Name: " . $row['product_name'] . "\n";
    echo "Image Path: " . $row['image_path'] . "\n";
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

