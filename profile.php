<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $newUsername = $_POST['new_username'];
        $userId = $_SESSION['user_id'];

        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newUsername, $userId);

        if ($stmt->execute()) {
            $_SESSION['username'] = $newUsername;
            $success_message = "Your username has been updated!";
        } else {
            $error_message = "Failed to update username.";
        }

        $stmt->close();
    }
}
$conn->close();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Change Information</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Change Information</h1>

        <?php
        if (isset($success_message)) {
            echo '<p style="color: green;">' . $success_message . '</p>';
        } elseif (isset($error_message)) {
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>

        <form method="post">
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" required>
            <input type="submit" name="update_profile" value="Update Profile">
        </form>
    </div>
</body>

</html>