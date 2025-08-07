<?php
require_once(__DIR__ . '/../db_connect.php');

$message = "";

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM package WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Package deleted successfully.";
    } else {
        $message = "Failed to delete package.";
    }
    $stmt->close();
}

// Handle Edit/Update
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../product_img/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        $query = "UPDATE package SET name=?, image=?, stock=?, dis=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssisi", $name, $image, $stock, $description, $id);
    } else {
        $query = "UPDATE package SET name=?, stock=?, dis=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sisi", $name, $stock, $description, $id);
    }

    if ($stmt->execute()) {
        $message = "Package updated successfully.";
    } else {
        $message = "Failed to update package.";
    }
    $stmt->close();
}

// Fetch data
$query = "SELECT * FROM package";
$result = $conn->query($query);

// Fetch package for editing
$editData = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $editQuery = "SELECT * FROM package WHERE id = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $editResult = $stmt->get_result();
    $editData = $editResult->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Packages</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <h2>Manage Packages</h2>
    <?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
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
                    <a href="?edit_id=<?= $row['id'] ?>">Edit</a> | 
                    <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php if ($editData) { ?>
        <h3>Edit Package</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <label>Name:</label>
            <input type="text" name="name" value="<?= $editData['name'] ?>" required><br>

            <label>Image:</label>
            <input type="file" name="image"><br>
            <img src="../product_img/<?= $editData['image'] ?>" width="100"><br>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?= $editData['stock'] ?>" required><br>

            <label>Description:</label>
            <textarea name="description" required><?= $editData['dis'] ?></textarea><br>

            <button type="submit" name="update">Update Package</button>
        </form>
    <?php } ?>
</body>
</html>
