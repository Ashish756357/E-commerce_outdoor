<?php
$servername = "localhost";
$username = "root";  // Change if needed
$password = "";      // Change if needed
$dbname = "e commerce";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch banner images from database
$sql = "SELECT image_url FROM banners";
$result = $conn->query($sql);

$bannerImages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bannerImages[] = $row['image_url'];
    }
}
$conn->close();

// Convert PHP array to JavaScript JSON
echo json_encode($bannerImages);
?>
