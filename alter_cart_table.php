<?php
require_once 'db_connect.php';

$alter_query = "ALTER TABLE cart
                MODIFY COLUMN product_id INT NULL";

if ($conn->query($alter_query) === TRUE) {
    echo "Table cart altered successfully.";
} else {
    echo "Error altering table: " . $conn->error;
}

$conn->close();
?>
