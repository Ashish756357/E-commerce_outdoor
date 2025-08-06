<?php
require_once __DIR__ . '/../db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$upload_dir = __DIR__ . '/../product_img/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to display package cards
function displayPackageCard($package) {
    $image_url = 'http://localhost/hello/product_img/' . htmlspecialchars($package['image']);

    echo '<div class="package-card">';
    echo '<img src="' . $image_url . '" alt="' . htmlspecialchars($package['name']) . '">';
    
    echo '<div class="package-content">';
    // echo '<h3>' . htmlspecialchars($package['name']) . '</h3>';
    // echo '<p class="stock">Stock: ' . (int)$package['stock'] . '</p>';
    // echo '<p class="description">' . nl2br(htmlspecialchars($package['dis'])) . '</p>';
    echo '</div>';
    
    echo '</div>';
}

$query = "SELECT * FROM package";
$result = $conn->query($query);

echo '<div class="package-container">';

if ($result->num_rows > 0) {
    while ($package = $result->fetch_assoc()) {
        displayPackageCard($package);
    }
} else {
    echo "<p>No packages available.</p>";
}

echo '</div>';

$conn->close();
?>
