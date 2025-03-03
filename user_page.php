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
    <link rel="stylesheet" href="assets/style.css"> <!-- Corrected CSS Path -->


</head>
<body>

    <!-- Include Sidebar -->
    <?php include 'components/sidebar.php'; ?>
    <!-- Include JavaScript File -->
    <script src="assets/js/sidebar.js"></script>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">My E-Commerce</div>
        <input type="text" placeholder="Search for products...">
        <div class="icons">
            <?php include 'components/profile.php'; ?> <!-- Profile before Cart -->
            <a href="cart.php">🛒 Cart</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

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

 
</body>
</html>
