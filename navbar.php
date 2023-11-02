<?php
session_start();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer') {
    header("Location: permission_failed.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Log the user out and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<div class="navbar">
    <a class="logo" href="dashboard.php">
        <img src="logo.png" alt="Logo" height="48px">
    </a>
    <div class="user-info">
        <span id="username"><?php echo $_SESSION['username']; ?></span>
        <div class="dropdown-content" id="userDropdown">
            <a href="profile.php">Change Information</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>