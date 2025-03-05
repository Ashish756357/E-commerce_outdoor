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
    <title>My E-Commerce</title>
    <link rel="stylesheet" href="/hello/assets/css/style.css"> 
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/auto-switch.js"></script>
    <link rel="stylesheet" href="\hello\assets\css\banner_mg.css">
    <script src="\hello\assets\js\banner_mg.js"></script>
    <!-- Include JavaScript File -->
</head>

<body>
    <!-- Include Sidebar -->
    <?php include 'components/sidebar.php'; ?>
    <!-- Navbar -->
    <?php include 'components/navbar.php'; ?>
    <!-- Scrollable Categories -->
    <div class="categories">
        <a href="#">Tent</a>
        <a href="#">Shoes</a>
        <a href="#">Clothes</a>
        <a href="#">Bag</a>
        <a href="#">Toys</a>
        <a href="#">Home</a>
    </div>
    <?php include 'components/banner.php'; ?>
    <?php include 'components/product_card.php'; ?>

 
</body>
</html>
