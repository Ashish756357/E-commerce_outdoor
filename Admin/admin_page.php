<?php
require_once(__DIR__ . '/../db_connect.php');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
<link rel="stylesheet" href="../assets/css/admin.css">



</head>
<body>
    <nav>
        <h2>Admin Panel</h2>
        <a href="\hello\components_admin\users.php">User Management</a>

        <a href="\hello\components_admin\product_mg.php">Product Management</a>
        <a href="sales.php">Sales Overview</a>
        <a href="\hello\components_admin\banner_mg.php">Manage Banners</a>
        <a href="/hello/login_form.php">Logout</a>     
    </nav>

    <section>
        <h2>Dashboard</h2>
        <div class="dashboard-box">
            <p>Total Users: <?php echo $total_users; ?></p>
            <p>Total Products: <?php echo $total_products; ?></p>
            <p>Total Sales: ₹<?php echo number_format($total_sales, 2); ?></p>
        </div>
    </section>
</body>
</html>
