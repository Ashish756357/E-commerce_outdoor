<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
$initial = strtoupper(substr($username, 0, 1)); // Get first letter
?>
<div class="profile-container">
    <div class="profile-icon" title="<?php echo htmlspecialchars($username); ?>" onclick="toggleDropdown()">
        <?php echo htmlspecialchars($initial); ?>
    </div>
    <div id="profileDropdown" class="dropdown-content">
            <a href="#">👤 My Account</a>
            <a href="#">📦 Track Order</a>
            <a href="#">📝 Feedback</a>
            <a href="logout.php" class="logout"> Logout</a>
    </div>
</div>

<style>




/* Dropdown Content */
.dropdown-content {
    display: none;
    position: absolute;
    top: 120%; 
    left: -93%; 
    background-color: #000000ff;
    min-width: 160px; 
    max-width: 250px;  
    text-align: center;
    padding: 8px;  /* Reduced padding to make the content tighter */
    z-index: 1200;
    border-radius: 8px;
}






/* Hover effect for links */
.dropdown-content a:hover {
    background-color: #ddd;
}

/* Show dropdown when clicked */
.show {
    display: block;
}
</style>

<script>
// JavaScript function to toggle the dropdown menu
function toggleDropdown() {
    var dropdown = document.getElementById("profileDropdown");
    dropdown.classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    var dropdown = document.getElementById("profileDropdown");
    var profileIcon = document.querySelector('.profile-icon');

    // If the click is outside the dropdown and the profile icon
    if (!dropdown.contains(event.target) && !profileIcon.contains(event.target)) {
        dropdown.classList.remove("show");
    }
}

</script>
