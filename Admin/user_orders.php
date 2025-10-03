<?php
require_once(__DIR__ . '/../db_connect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch all user orders details from cart joined with users, products, and packages
$query = $conn->prepare("
    SELECT u.id AS user_id, u.username, c.product_id, c.package_id, c.quantity, c.booking_date, c.special_requests,
           p.name AS product_name, pkg.name AS package_name
    FROM cart c
    JOIN users u ON c.user_id = u.id
    LEFT JOIN products p ON c.product_id = p.id
    LEFT JOIN package pkg ON c.package_id = pkg.id
    ORDER BY u.username, c.added_at DESC
");
$query->execute();
$result = $query->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Orders</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/user_order.css">
</head>
<body>
    <h2>User Orders Details</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Product Name</th>
                <th>Package Name</th>
                <th>Quantity</th>
                <th>Booking Date</th>
                <th>Special Requests</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td><?php echo htmlspecialchars($order['product_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($order['package_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['booking_date'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($order['special_requests'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
