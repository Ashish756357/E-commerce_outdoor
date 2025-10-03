<?php
require_once __DIR__ . '/../db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to modify the cart.");
}

$user_id   = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$package_id = $_POST['package_id'] ?? null;
$action     = $_POST['action'] ?? null;

if ((!$product_id && !$package_id) || !$action) {
    die("Invalid request.");
}

$where_clause = $product_id ? "product_id = ?" : "package_id = ?";
$param = $product_id ?? $package_id;
$param_type = "ii";

// Fetch the current quantity of the item in the cart
$query = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND $where_clause");
$query->bind_param("i" . ($product_id ? "i" : "i"), $user_id, $param);
$query->execute();
$result = $query->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found in cart.");
}

$current_quantity = $item['quantity'];

if ($action === 'increase') {
    $new_quantity = $current_quantity + 1;
} elseif ($action === 'decrease') {
    $new_quantity = $current_quantity - 1;
} else {
    die("Invalid action.");
}

if ($new_quantity > 0) {
    // Update the quantity in the cart
    $update_cart = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND $where_clause");
    $update_cart->bind_param("ii" . ($product_id ? "i" : "i"), $new_quantity, $user_id, $param);
    $update_cart->execute();
} else {
    // Remove the item from the cart if quantity is zero or less
    $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND $where_clause");
    $delete_cart->bind_param("i" . ($product_id ? "i" : "i"), $user_id, $param);
    $delete_cart->execute();
}

header("Location: cart.php");
exit;
?>
