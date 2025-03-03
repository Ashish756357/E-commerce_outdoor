<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Access Denied!");
}

$upload_dir = __DIR__ . "/../uploads/"; // Directory to store uploaded images
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Create directory if not exists
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["banner_image"])) {
    $file_name = basename($_FILES["banner_image"]["name"]);
    $target_file = $upload_dir . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.history.back();</script>";
        exit;
    }

    // Move file to uploads directory
    if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
        $image_url = "uploads/" . time() . "_" . $file_name; // Store relative path

        // Insert image URL into database
        $stmt = $conn->prepare("INSERT INTO banners (image_url) VALUES (?)");
        $stmt->bind_param("s", $image_url);
        if ($stmt->execute()) {
            echo "<script>alert('Banner uploaded successfully!'); window.location.href='banner.php';</script>";
        } else {
            echo "<script>alert('Database error!');</script>";
        }
    } else {
        echo "<script>alert('Error uploading file!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Banner</title>
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body>

<h2>Upload Banner Image</h2>
<form action="" method="POST" enctype="multipart/form-data">
    <label>Select Banner Image:</label>
    <input type="file" name="banner_image" required>
    <button type="submit">Upload</button>
</form>

<h2>Current Banner</h2>
<?php
// Fetch the latest banner
$result = $conn->query("SELECT image_url FROM banners ORDER BY uploaded_at DESC LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    echo "<img src='../" . htmlspecialchars($row['image_url']) . "' alt='Banner Image' style='max-width:100%; height:auto;'>";
} else {
    echo "<p>No banner uploaded yet.</p>";
}
?>

</body>
</html>
