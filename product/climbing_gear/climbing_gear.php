<?php
// Include your database connection file which defines $conn
require_once '/hello/db_connect.php';

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * LEFT COLUMN: Fetch rope products from the "products" table
 */
$searchTerm = "%rope%";
$sql = "SELECT id, name, price, image
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

/**
 * RIGHT COLUMN: Fetch from the "climbing_gear" table
 * (assuming `id`, `title`, `price`, `image_url` exist)
 */
$gearStmt = $conn->query("SELECT id, title, price, image_url FROM climbing_gear");
$climbingGear = [];
while ($row = $gearStmt->fetch_assoc()) {
    $climbingGear[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products Display with Persistent Dropdown</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /************************************
     * NAVBAR STYLES
     ************************************/
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    /* The navbar container */
    .navbar {
      display: flex;
      align-items: center;
      background: #3d3c3c; /* Dark gray background */
      color: #fff;
      padding: 0 20px;
      height: 60px;
      position: relative;
    }

    /* Menu icon (three bars) - optional */
    .menu-icon {
      display: inline-block;
      cursor: pointer;
      margin-right: 20px;
    }
    .menu-icon div {
      width: 25px;
      height: 3px;
      background-color: #fff;
      margin: 5px 0;
    }

    /* Navbar links */
    .navbar a {
      color: #fff;
      text-decoration: none;
      margin-right: 15px;
      font-size: 16px;
      padding: 5px 0;
    }
    .navbar a:hover {
      color: #f0c14b; /* Gold-like hover color */
    }

    /* The horizontal <ul> for dropdown and other links */
    .menu {
      list-style: none;
      margin: 0;
      padding: 0;
      display: inline-flex;
      align-items: center;
    }
    .menu > li {
      position: relative;
    }

    /* ------ Persistent Dropdown using Checkbox Hack ------ */
    /* Hide the checkbox but keep it in the DOM */
    .dropdown input[type="checkbox"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    /* Style the label as the dropbtn */
    .dropdown .dropbtn {
      cursor: pointer;
      display: inline-block;
      padding: 5px 0;
      color: #fff;
      text-decoration: none;
    }

    /* Dropdown content container */
    .dropdown-content {
      display: none;
      position: absolute;
      top: 40px; /* Show just below the navbar */
      background-color: #4b4b4b;
      min-width: 200px;
      border-radius: 4px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
      z-index: 999; /* on top of other elements */
      padding: 10px;
    }

    /* Show dropdown when the checkbox is checked */
    .dropdown input[type="checkbox"]:checked ~ .dropdown-content {
      display: block;
    }

    /* Each column or link in dropdown */
    .dropdown-content .dropdown-row {
      display: flex;
      gap: 20px;
    }
    .dropdown-content .column h3 {
      color: #f0c14b;
      margin: 5px 0;
      font-size: 16px;
    }
    .dropdown-content .column a {
      display: block;
      color: #fff;
      padding: 3px 0;
      text-decoration: none;
      font-size: 14px;
    }
    .dropdown-content .column a:hover {
      text-decoration: underline;
    }
    /* ------ End Persistent Dropdown ------ */

    /* Search box (far right) */
    .categories-search {
      margin-left: auto; /* pushes it to the right */
      padding: 6px 10px;
      border-radius: 20px;
      border: 1px solid #ccc;
      outline: none;
      font-size: 14px;
      background-color: #fff;
      color: #333;
    }
    .categories-search::placeholder {
      color: #888;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
      .navbar {
        flex-wrap: wrap;
        height: auto;
      }
      .menu-icon {
        margin-bottom: 10px;
      }
      .categories-search {
        margin: 10px 0 0 auto;
      }
    }

    /************************************
     * PRODUCT CARDS (existing styles)
     ************************************/
    .container {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      gap: 20px;
    }
    .product-container, .gear-container {
      width: 45%;
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }
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
    .card img {
      width: 100%;
      height: auto;
      object-fit: contain;
      border-radius: 8px;
      margin-bottom: 15px;
      transition: transform 0.3s ease-in-out;
    }
    .card img:hover {
      transform: scale(1.1);
    }
    .card h3 {
      font-size: 22px;
      color: #4B0082;
      margin: 15px 0 10px;
    }
    .card p {
      font-size: 18px;
      color: #6A5ACD;
      font-weight: bold;
      margin: 10px 0;
    }
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
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
      }
      .product-container, .gear-container {
        width: 90%;
      }
    }
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

<!-- NAVBAR -->
<div class="navbar">
  <!-- (Optional) Menu icon -->
  <div class="menu-icon">
    <div></div>
    <div></div>
    <div></div>
  </div>
  <a href="#">Home</a>
  <a href="#">Products</a>

  <!-- Dropdown using checkbox hack -->
  <ul class="menu">
    <li class="dropdown">
      <!-- Hidden checkbox for toggling dropdown -->
      <input type="checkbox" id="sale-toggle">
      <!-- Label acts as the dropbtn -->
      <label for="sale-toggle" class="dropbtn">Sale! ▼</label>
      <!-- Dropdown content -->
      <div class="dropdown-content">
        <div class="dropdown-row">
          <div class="column">
            <h3>Camping Gear</h3>
            <a href="/hello/product/Backpack/backpack.php">Backpacks</a>
            <a href="#">Tents & Shelters</a>
            <a href="#">Sleeping bags</a>
          </div>
          <div class="column">
            <h3>Climbing Gear</h3>
            <a href="#">Climbing Shoes</a>
            <a href="#">Harnesses</a>
            <a href="/hello/product/climbing_ropes/climbing_rope.php">Ropes</a>
          </div>
          <div class="column">
            <h3>Clothing</h3>
            <a href="#">Men's Clothing</a>
            <a href="#">Women's Clothing</a>
          </div>
          <div class="column">
            <h3>Footwear</h3>
            <a href="#">Men's Footwear</a>
            <a href="#">Women's Footwear</a>
          </div>
        </div>
      </div>
    </li>
  </ul>

  <a href="#">Best Sellers</a>
  <a href="cart_user/cart.php">My Order</a>
  <a href="cart_user/info.php">Info</a>

  <!-- Search box -->
  <input type="text" placeholder="Search for products..." class="categories-search">
</div>
<!-- END NAVBAR -->

<!-- MAIN CONTENT (PRODUCT CARDS) -->
<div class="container">
  <!-- LEFT COLUMN: Rope products from the "products" table -->
  <div class="product-container">
    <?php foreach ($products as $product): ?>
      <?php 
        // Build the full URL for the product image
        $product_image_url = '/hello/' . htmlspecialchars($product['image']);
      ?>
      <div class="card">
        <img src="<?= $product_image_url ?>" alt="Product Image">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p>Price: ₹<?= htmlspecialchars($product['price']) ?></p>
        <!-- "Add to Cart" form -->
        <form action="/hello/cart_user/add_to_cart.php" method="POST" style="margin-top: 20px;">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit" class="add-to-cart">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- RIGHT COLUMN: Items from "climbing_gear" table -->
  <div class="gear-container">
    <?php foreach ($climbingGear as $gear): ?>
      <div class="card">
        <img src="<?= htmlspecialchars($gear['image_url']) ?>" alt="Climbing Gear">
        <h3><?= htmlspecialchars($gear['title']) ?></h3>
        <p>Price: ₹<?= htmlspecialchars($gear['price']) ?></p>
        <!-- "Add to Cart" form -->
        <form action="/hello/add_to_cart.php" method="POST" style="margin-top: 20px;">
          <input type="hidden" name="product_id" value="<?= $gear['id'] ?>">
          <button type="submit" class="add-to-cart">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
