<?php
require_once __DIR__ . '/../db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Access Denied!");
}

// Handle Add Product Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image = $_POST['image']; // Image URL or path

    $sql = "INSERT INTO products (name, price, stock, description, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiss", $name, $price, $stock, $description, $image);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding product');</script>";
    }
}

// Handle Remove Product Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product removed successfully!');</script>";
    } else {
        echo "<script>alert('Error removing product');</script>";
    }
}

// Fetch all products from the database
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="\hello\assets\css\product_mgmt.css">
</head>
<body>

    <h2>Admin Panel - Manage Products</h2>

    <!-- Add Product Form -->
    <form method="POST" action="">
        <h3>Add New Product</h3>
        <label>Name:</label>
        <input type="text" name="name" required>
        
        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>
        
        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Image URL:</label>
        <input type="file" name="image">

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <hr>

    <!-- Remove Product Section -->
    <h3>Existing Products</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price (₹)</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php while ($product = $products->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td>₹<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo $product['stock']; ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="remove_product">Remove</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
