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
    
    // Image Section with Stock Badge
    echo '<div class="card-image">';
    echo '<img src="' . $image_url . '" alt="' . htmlspecialchars($package['name']) . '">';
    echo '<span class="stock-badge">' . (int)$package['stock'] . ' Available</span>';
    echo '</div>';

    // Content Section
    echo '<div class="card-content">';
    echo '<h3 class="card-title">' . htmlspecialchars($package['name']) . '</h3>';
    echo '<p class="description">' . nl2br(htmlspecialchars($package['dis'])) . '</p>';
    echo '<a href="package_detail.php?id=' . $package['id'] . '" class="book-btn">View Details & Book</a>';
    echo '</div>';

    echo '</div>';
}

$query = "SELECT * FROM package";
$result = $conn->query($query);

echo '<section id="packages" class="package-section">';
echo '<div class="section-header">';
echo '<h2>🌍 Explore Our Packages</h2>';
echo '<p>Discover the best travel experiences tailored just for you</p>';
echo '</div>';

echo '<div class="package-container">';

if ($result->num_rows > 0) {
    while ($package = $result->fetch_assoc()) {
        displayPackageCard($package);
    }
} else {
    echo "<p>No packages available.</p>";
}

echo '</div>';
echo '</section>';

$conn->close();
?>
