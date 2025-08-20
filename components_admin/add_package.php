<?php
require_once(__DIR__ . '/../db_connect.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$upload_dir = __DIR__ . '/../product_img/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $stock = intval($_POST['stock']);
    $dis = trim($_POST['dis']);
    $image = $_FILES['image'];

    // Basic validation
    if (empty($name) || empty($dis) || $image['error'] !== 0) {
        $error = 'Please fill in all fields and upload an image.';
    } else {
        $image_name = basename($image['name']);
        $target_file = $upload_dir . $image_name;

        // Move the uploaded file
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO package (name, image, stock, dis) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $name, $image_name, $stock, $dis);

            if ($stmt->execute()) {
                $success = "Package added successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error = "Failed to upload image.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Package</title>
    <link rel="stylesheet" href="../assets/css/add_package_mg.css">
</head>
<body>
    <h2>Add New Package</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Package Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" min="0" required><br><br>

        <label>Description:</label><br>
        <textarea name="dis" rows="4" required></textarea><br><br>

        <label>Image:</label><br>
        <input type="file" name="image" accept="image/*" required><br><br>

        <button type="submit">Add Package</button>
    </form>

    <br>
    <a href="/hello/Admin/package_mgmt.php">⬅ Back to Package Management</a>
</body>
</html>
