<?php
// Include your database connection file which defines $conn
require_once '/hello/db_connect.php';

$productName = "Bag"; // Change this to the exact name you want
$stmt = $conn->prepare("SELECT name, price, image FROM products WHERE name = ? LIMIT 1");
$stmt->bind_param("s", $productName);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Fetch one sleeping bag from the sleeping_bags table
$sleepingBagStmt = $conn->query("SELECT title, price, image_url FROM sleeping_bags LIMIT 1");
$sleepingBag = $sleepingBagStmt->fetch_assoc();

// Build the full URL for the product image using the same method as displayProductCard
$product_image_url = 'http://localhost/hello/' . htmlspecialchars($product['image']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Products Display</title>
  <style>
    .container {
      display: flex;
      justify-content: space-between;
      padding: 20px;
    }
    .card {
      border: 1px solid #ddd;
      border-radius: 10px;
      width: 45%;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 20px;
      text-align: center;
    }
    .card img {
      width: 100%;
      max-height: 200px;
      object-fit: cover;
      border-radius: 5px;
    }
    .card h3 {
      margin: 15px 0 10px;
    }
    .card p {
      font-weight: bold;
      color: green;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Left Card - Specific Product by name -->
  <div class="card">
    <img 
      src="<?= $product_image_url ?>" 
      alt="Product Image"
    >
    <h3><?= htmlspecialchars($product['name']) ?></h3>
    <p>Price: ₹<?= htmlspecialchars($product['price']) ?></p>
  </div>

  <!-- Right Card - Sleeping Bag -->
  <div class="card">
    <img 
      src="<?= htmlspecialchars($sleepingBag['image_url']) ?>" 
      alt="Sleeping Bag"
    >
    <h3><?= htmlspecialchars($sleepingBag['title']) ?></h3>
    <p>Price: ₹<?= htmlspecialchars($sleepingBag['price']) ?></p>
  </div>
</div>

</body>
</html>
