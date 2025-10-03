<?php
require_once __DIR__ . '/../db_connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add items to the cart.");
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['product_id'])) {
    // Handle product
    $product_id = $_POST['product_id'];
    $package_id = null;

    // Check if the product exists
    $item_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $item_query->bind_param("i", $product_id);
    $item_query->execute();
    $item_result = $item_query->get_result();

    if ($item_result->num_rows == 0) {
        die("Product not found.");
    }

    $item = $item_result->fetch_assoc();
    $item_type = 'product';
    $quantity = 1;
    $booking_date = null;
    $special_requests = null;

} elseif (isset($_POST['package_id'])) {
    // Handle package
    $package_id = $_POST['package_id'];
    $product_id = null;
    $quantity = $_POST['quantity'] ?? 1;
    $booking_date = $_POST['booking_date'] ?? null;
    $special_requests = $_POST['special_requests'] ?? null;

    // Check if the package exists
    $item_query = $conn->prepare("SELECT * FROM package WHERE id = ?");
    $item_query->bind_param("i", $package_id);
    $item_query->execute();
    $item_result = $item_query->get_result();

    if ($item_result->num_rows == 0) {
        die("Package not found.");
    }

    $item = $item_result->fetch_assoc();
    $item_type = 'package';

} else {
    die("Invalid item.");
}

// Check if the item is already in the cart
$cart_check_query = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND ((product_id = ? AND product_id IS NOT NULL) OR (package_id = ? AND package_id IS NOT NULL))");
$cart_check_query->bind_param("iii", $user_id, $product_id, $package_id);
$cart_check_query->execute();
$cart_check_result = $cart_check_query->get_result();

if ($cart_check_result->num_rows > 0) {
    // For products, update quantity; for packages, perhaps don't allow duplicates or update quantity
    if ($item_type == 'product') {
        $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $update_cart->bind_param("ii", $user_id, $product_id);
        $update_cart->execute();
    } else {
        // For packages, perhaps update quantity or do nothing
        $update_cart = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND package_id = ?");
        $update_cart->bind_param("iii", $quantity, $user_id, $package_id);
        $update_cart->execute();
    }
} else {
    // Insert into cart
    $insert_cart = $conn->prepare("INSERT INTO cart (user_id, product_id, package_id, quantity, booking_date, special_requests) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_cart->bind_param("iiiiss", $user_id, $product_id, $package_id, $quantity, $booking_date, $special_requests);
    $insert_cart->execute();
}

// Update session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$key = $product_id ?? $package_id;
if (isset($_SESSION['cart'][$key])) {
    if ($item_type == 'product') {
        $_SESSION['cart'][$key]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$key]['quantity'] = $quantity;
    }
} else {
    $_SESSION['cart'][$key] = [
        'name' => $item['name'],
        'price' => $item['price'] ?? 0, // Packages may not have price, adjust as needed
        'image' => $item['image'],
        'quantity' => $quantity,
        'type' => $item_type,
        'booking_date' => $booking_date,
        'special_requests' => $special_requests
    ];
}

header("Location: cart.php");
exit;
?>
