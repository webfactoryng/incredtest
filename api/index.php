<?php

// Namespace
use IncredTest\Path;
// Include router class
include 'Path.php';

// Define a global basepath
define('BASEPATH','/');

// Get all products path
Path::add('/products', function() {
header('Content-Type: application/json');

require_once('connection.php');

    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
});

// Get 1 specific product
Path::add('/products/(.*)', function($productID) {
    header('Content-Type: application/json');
    
    require_once('connection.php');
    
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $productID);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($product);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
});


//Add product path
Path::add('/add/product', function() {
header('Content-Type: application/json');

require_once('connection.php');
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $price = str_replace(',', '', $price);

        // Validate input
        if (empty($title) || empty($description) || empty($price)) {
            echo json_encode(['error' => 'Title, description and price are required']);
            exit();
        }
 
        try {
            $stmt = $conn->prepare("INSERT INTO products (title, description, price) VALUES (?, ?, ?)");
            $stmt->execute([$title, $description, $price]);
            echo json_encode(['message' => 'Product added successfully']); 
        } catch(PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();

    // for Invalid request method
    echo json_encode(['error' => 'Invalid request method']);
    exit();


}, ['post']);

Path::add('/update/product', function() {
    header('Content-Type: application/json');
  
    require_once('connection.php');
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $title = $_POST['title'];
      $description = $_POST['description'];
      $price = $_POST['price'];
      $price = str_replace(',', '', $price);
  
      // Validate input
      if (empty($id) || empty($title) || empty($description) || empty($price)) {
        echo json_encode(['error' => 'ID, title, description, and price are required']);
        exit();
      }
  
      try {
        $stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ? WHERE id = ?");
        $stmt->execute([$title, $description, $price, $id]);
        echo json_encode(['message' => 'Product updated successfully']);
      } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
      }
      exit();
    }
  
    // Invalid request method
    echo json_encode(['error' => 'Invalid request method']);
    exit();
  }, ['post']);

// Run the Path with the given Basepath
Path::run(BASEPATH);

