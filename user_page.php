<?php
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearQuest</title>
    <link rel="stylesheet" href="/hello/assets/css/style.css"> 
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/auto-switch.js"></script>
    <!-- Include JavaScript File -->
</head>

<body>
    <!-- Include Sidebar -->
    <?php include 'components/sidebar.php'; ?>
    <!-- Navbar -->
    <?php include 'components/navbar.php'; ?>
    <!-- Scrollable Categories with Search Bar -->
    <div class="categories">
        <a href="#">Home</a>
        <a href="#">Tent</a>
        <a href="#">Shoes</a>
        <a href="#">Clothes</a>
        <a href="#">Bag</a>
        <a href="#">Toys</a>
        <input type="text" placeholder="Search for products..." class="categories-search"> 
    </div>
    <?php include 'components/banner.php'; ?>
    <?php include 'components/product_card.php'; ?>
</body>
</html>
