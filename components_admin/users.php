<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle New User Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $new_username = $_POST['new_username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash password
    $new_role = $_POST['new_role'];

    $insert_query = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $insert_query->bind_param("sss", $new_username, $new_password, $new_role);

    if ($insert_query->execute()) {
        echo "<script>alert('New user added successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Error adding user!');</script>";
    }
}

// Handle User Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $username = $_POST['username'];
    $role = $_POST['role'];

    $update_query = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    $update_query->bind_param("ssi", $username, $role, $user_id);

    if ($update_query->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Error updating user!');</script>";
    }
}

// Handle User Deletion
if (isset($_GET['delete_id'])) {
    $user_id = intval($_GET['delete_id']);

    if ($user_id == $_SESSION['admin_id']) {
        echo "<script>alert('You cannot delete yourself!'); window.location.href='users.php';</script>";
        exit;
    }

    $delete_query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_query->bind_param("i", $user_id);

    if ($delete_query->execute()) {
        echo "<script>alert('User deleted successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
}

// Fetch Users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../assets/user_mgmt.css">
</head>
<body>

<h2>User Management</h2>

<!-- Add New User Form -->
<h3>Add New User</h3>
<form method="POST">
    <label>Username:</label>
    <input type="text" name="new_username" required>
    
    <label>Password:</label>
    <input type="password" name="new_password" required>
    
    <label>Role:</label>
    <select name="new_role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    
    <button type="submit" name="add_user">Add User</button>
</form>

<!-- User List -->
<table>
    <tr>
        <th>ID</th><th>Username</th><th>Role</th><th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </td>
            <td>
                <select name="role">
                    <option value="user" <?php echo ($row['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </td>
            <td>
                <button type="submit" name="update_user">Update</button>
                </form>
                <a href="users.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
