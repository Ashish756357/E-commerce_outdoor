<?php
require_once __DIR__ . '/../db_connect.php';

// Fetch all banner images
$result = $conn->query("SELECT image_url FROM banners ORDER BY uploaded_at DESC");

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = 'hello/' . ltrim(htmlspecialchars($row['image_url']), '/');
}
?>

<div class="banner-container">
    <?php if (!empty($images)): ?>
        <img id="bannerImage" src="http://localhost/<?php echo $images[0]; ?>" alt="Banner Image" style="width:100%; height:auto;">
    <?php else: ?>
        <p>No banner available.</p>
    <?php endif; ?>
</div>

<script>
    let images = <?php echo json_encode($images); ?>;
    let index+ = 0;

    function changeBanner() {
        if (images.length > 1) {
            index = (index + 1) % images.length;
            document.getElementById("bannerImage").src = "http://localhost/" + images[index];
        }
    }

    setInterval(changeBanner, 5000); // Change every 5 seconds
</script>