<?php
require_once(__DIR__ . '/../db_connect.php');
session_start();

// Query and display packages for management
$query = "SELECT * FROM package";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Packages</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <h2>Manage Packages</h2>
    <a href="../components_admin/add_package.php">+ Add New Package</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><img src="../product_img/<?= $row['image'] ?>" width="100" /></td>
                <td><?= $row['stock'] ?></td>
                <td><?= $row['dis'] ?></td>
                <td>
                    <a href="edit_package.php?id=<?= $row['id'] ?>">Edit</a> | 
                    <a href="delete_package.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
