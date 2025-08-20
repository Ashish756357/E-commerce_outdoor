<?php
require_once 'db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get package ID from URL
$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($package_id <= 0) {
    header("Location: user_page.php");
    exit();
}

// Fetch package details
$query = "SELECT * FROM package WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: user_page.php");
    exit();
}

$package = $result->fetch_assoc();
$stmt->close();

$image_url = 'http://localhost/hello/product_img/' . htmlspecialchars($package['image']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($package['name']); ?> - Package Details</title>
    <link rel="stylesheet" href="assets/css/package_detail.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="package-detail-container">
        <div class="breadcrumb">
            <a href="user_page.php">Home</a> >
            <a href="user_page.php#packages">Packages</a> >
            <span><?php echo htmlspecialchars($package['name']); ?></span>
        </div>

        <div class="package-detail-header">
            <h1><?php echo htmlspecialchars($package['name']); ?></h1>
            <div class="availability-badge">
                <span class="stock-info"><?php echo (int)$package['stock']; ?> Available</span>
            </div>
        </div>

        <div class="package-detail-content">
            <div class="package-image-section">
                <div class="main-image">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($package['name']); ?>">
                </div>
            </div>

            <div class="package-info-section">
                <div class="package-description">
                    <h3>Package Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($package['dis'])); ?></p>
                </div>

                <div class="package-features">
                    <h3>Package Features</h3>
                    <ul>
                        <li>✈️ Flight tickets included</li>
                        <li>🏨 Premium accommodation</li>
                        <li>🍽️ Daily breakfast & dinner</li>
                        <li>🚗 Airport transfers</li>
                        <li>📸 Guided tours</li>
                        <li>🛡️ Travel insurance</li>
                    </ul>
                </div>

                <div class="booking-section">
                    <h3>Book This Package</h3>
                    <form id="bookingForm" method="POST" action="cart_user/add_to_cart.php">
                        <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                        <input type="hidden" name="package_name" value="<?php echo htmlspecialchars($package['name']); ?>">
                        <input type="hidden" name="package_image" value="<?php echo htmlspecialchars($package['image']); ?>">
                        
                        <div class="form-group">
                            <label for="quantity">Number of Persons:</label>
                            <select name="quantity" id="quantity" required>
                                <?php for($i = 1; $i <= min($package['stock'], 10); $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> Person<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="booking_date">Preferred Travel Date:</label>
                            <input type="date" name="booking_date" id="booking_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="special_requests">Special Requests (Optional):</label>
                            <textarea name="special_requests" id="special_requests" rows="3" placeholder="Any special requirements or preferences..."></textarea>
                        </div>

                        <button type="submit" class="book-now-btn">
                            <span>Add to Cart</span>
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="package-recommendations">
            <h3>You Might Also Like</h3>
            <div class="recommendations-container">
                <?php
                // Fetch 3 random packages excluding current one
                $rec_query = "SELECT * FROM package WHERE id != ? ORDER BY RAND() LIMIT 3";
                $rec_stmt = $conn->prepare($rec_query);
                $rec_stmt->bind_param("i", $package_id);
                $rec_stmt->execute();
                $rec_result = $rec_stmt->get_result();
                
                while($rec_package = $rec_result->fetch_assoc()):
                    $rec_image_url = 'http://localhost/hello/product_img/' . htmlspecialchars($rec_package['image']);
                ?>
                    <div class="rec-package-card">
                        <a href="package_detail.php?id=<?php echo $rec_package['id']; ?>">
                            <img src="<?php echo $rec_image_url; ?>" alt="<?php echo htmlspecialchars($rec_package['name']); ?>">
                            <h4><?php echo htmlspecialchars($rec_package['name']); ?></h4>
                            <span class="view-details">View Details</span>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script>
        // Set minimum date to tomorrow
        document.getElementById('booking_date').min = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    </script>
</body>
</html>
<?php
$conn->close();
?>
