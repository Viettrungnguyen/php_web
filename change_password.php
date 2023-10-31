<?php
session_start();

// Check if the user is logged in, redirect to the login page if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection (replace with your database credentials)
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

// Check if the database connection was successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $userId = $_SESSION['user_id'];

        // Check if the current password matches the one in the database
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($currentPassword, $hashedPassword)) {
            $error_message = "Current password is incorrect.";
        } elseif ($newPassword !== $confirmPassword) {
            $error_message = "New password and confirmation do not match.";
        } else {
            // Hash the new password and update it in the database
            $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateSql = "UPDATE users SET password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $hashedNewPassword, $userId);

            if ($updateStmt->execute()) {
                $success_message = "Password updated successfully!";
            } else {
                $error_message = "Failed to update password.";
            }

            $updateStmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="navbar">
        <div class="user-menu">
            <span id="username"><?php echo $_SESSION['username']; ?></span>
            <div class="dropdown-content" id="userDropdown">
                <a href="profile.php">Change Information</a>
                <a href="change_password.php">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <h1>Change Password</h1>

    <?php
    if (isset($success_message)) {
        echo '<p style="color: green;">' . $success_message . '</p>';
    } elseif (isset($error_message)) {
        echo '<p style="color: red;">' . $error_message . '</p>';
    }
    ?>

    <form method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" name="change_password" value="Change Password">
    </form>
</body>

</html>