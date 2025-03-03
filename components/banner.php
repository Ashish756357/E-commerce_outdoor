<?php
require_once __DIR__ . '/../db_connect.php';

// Fetch the latest banner
$result = $conn->query("SELECT image_url FROM banners ORDER BY uploaded_at DESC LIMIT 1");
$row = $result ? $result->fetch_assoc() : null;

if ($row) {
    // Ensure correct path formatting
    $image_url = isset($row['image_url']) ? 'hello/' . ltrim(htmlspecialchars($row['image_url']), '/') : '';
} else {
    $image_url = '';
}
?>

<div class="banner-container">
    <?php if (!empty($image_url)): ?>
        <img src="http://localhost/<?php echo $image_url; ?>" alt="Banner Image" style="width:100%; height:auto;">
    <?php else: ?>
        <p>No banner available.</p>
    <?php endif; ?>
</div>
