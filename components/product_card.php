<?php
require_once __DIR__ . '/../db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Access Denied!");
}

$upload_dir = __DIR__ . '/../product_img/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function displayProductCard($product) {
    // Corrected image path
    $image_url = 'http://localhost/hello/product_img/' . htmlspecialchars($product['image']);

    echo '<div class="product-card">';
    echo '<img src="' . $image_url . '" alt="' . htmlspecialchars($product['name']) . '" style="max-width:100%; height:auto;">';
    echo '<!-- Debug: Image URL: ' . $image_url . ' -->';

    echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
    echo '<p>Price: ₹' . number_format($product['price'], 2) . '</p>';
    echo '<p>Stock: ' . htmlspecialchars($product['stock']) . '</p>';
    echo '<p>' . nl2br(htmlspecialchars($product['description'])) . '</p>';
    echo '<form method="POST" action="add_to_cart.php">';
    echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
    echo '<button type="submit">🛒 Add to Cart</button>';
    echo '</form>';
    echo '</div>';
}


$query = "SELECT * FROM products";
$result = $conn->query($query);

// Check if products exist
if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        displayProductCard($product); // Call the function to display the card
    }
} else {
    echo "<p>No products available.</p>";
}

$conn->close();
?>
