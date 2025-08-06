<?php
// Include your database connection file which defines $conn
require_once __DIR__ . '/../../db_connect.php';

// 1) Fetch products that contain "bag" in name or description
$searchTerm = "%bag%";
$sql = "SELECT name, price, image
        FROM products
        WHERE name LIKE ?
           OR description LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// 2) Fetch all sleeping bags (no limit)
$sleepingBagStmt = $conn->query("SELECT title, price, image_url FROM sleeping_bags LIMIT 7");
$sleepingBags = [];
while ($row = $sleepingBagStmt->fetch_assoc()) {
    $sleepingBags[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Products Display</title>
  <style>
    /* Main container holding two columns */
    .container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        gap: 20px;
    }

    /* Left Column: Products Card Container */
    .product-container {
        width: 45%;
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    /* Right Column: Sleeping Bag Cards Container */
    .sleeping-bag-container {
        width: 45%;
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    /* Card Styling for both columns */
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.25);
    }

    /* Card Image Styling */
    .card img {
        width: 100%;
        height: auto;  /* Maintain aspect ratio */
        object-fit: contain;  /* Ensures the entire image is visible without cropping */
        border-radius: 8px;
        margin-bottom: 15px;
        transition: transform 0.3s ease-in-out;
    }

    .card img:hover {
        transform: scale(1.1);
    }

    /* Titles */
    .card h3 {
        font-size: 22px;
        color: #4B0082;
        margin: 15px 0 10px;
    }

    /* Price Styling */
    .card p {
        font-size: 18px;
        color: #6A5ACD;
        font-weight: bold;
        margin: 10px 0;
    }

    /* Add to Cart Button */
    .add-to-cart {
        background: #333;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 20px;
        text-transform: uppercase;
    }

    .add-to-cart:hover {
        background: #4B0082;
    }

    /* Responsive Design for Tablets */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            align-items: center;
        }
        .product-container, .sleeping-bag-container {
            width: 90%;
        }
    }

    /* Responsive Design for Mobile */
    @media (max-width: 480px) {
        .card h3 {
            font-size: 18px;
        }
        .card p {
            font-size: 16px;
        }
        .add-to-cart {
            font-size: 14px;
            padding: 10px;
        }
        .card img {
            object-fit: contain;
        }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Left Column: Products that contain "bag" in name or description -->
  <div class="product-container">
    <?php foreach ($products as $product): ?>
      <?php 
        // Build the full URL for the product image
        $product_image_url = 'http://localhost/hello/' . htmlspecialchars($product['image']);
      ?>
      <div class="card">
        <img src="<?= $product_image_url ?>" alt="Product Image">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p>Price: ₹<?= htmlspecialchars($product['price']) ?></p>
        <button class="add-to-cart">Add to Cart</button>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Right Column: All Sleeping Bags -->
  <div class="sleeping-bag-container">
    <?php foreach ($sleepingBags as $sleepingBag): ?>
      <div class="card">
        <img src="<?= htmlspecialchars($sleepingBag['image_url']) ?>" alt="Sleeping Bag">
        <h3><?= htmlspecialchars($sleepingBag['title']) ?></h3>
        <p>Price: ₹<?= htmlspecialchars($sleepingBag['price']) ?></p>
        <button class="add-to-cart">Add to Cart</button>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
