<!DOCTYPE html>
<html>

<head>
    <title>Change Information</title>
    <link rel="stylesheet" type="text/css" href="stylePage.css">
    <style>
        /* Add your custom CSS styles here */
        .container {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        .form-input input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            box-sizing: border-box;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #000;
        }

        .form-input input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-input input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>
    <main class="container">
        <h1>Change Information</h1>

        <?php
        if (isset($success_message)) {
            echo '<p style="color: green;">' . $success_message . '</p>';
        } elseif (isset($error_message)) {
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>

        <form method="post" class="form-input">
            <label for="new_username">New Username:</label>
            <input type="text" placeholder="Enter username..." id="new_username" name="new_username" required value="<?php echo $oldUsername; ?>">
            <label for="new_phone">New Phone:</label>
            <input type="text" placeholder="Enter new phone number..." id="new_phone" name="new_phone" required value="<?php echo $oldPhone; ?>">
            <label for="new_address">New Address:</label>
            <input type="text" placeholder="Enter new address..." id="new_address" name="new_address" required value="<?php echo $oldAddress; ?>">
            <input type="submit" name="update_profile" value="Update Profile">
        </form>
    </main>
    <?php include 'footer.php'; ?>

</body>

</html>
<?php
// Connect to the database
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $newUsername = $_POST['new_username'];
        $newPhone = $_POST['new_phone'];
        $newAddress = $_POST['new_address'];
        $userId = $_SESSION['user_id'];

        $sql = "UPDATE users SET username = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $newUsername, $newPhone, $newAddress, $userId);

        if ($stmt->execute()) {
            $_SESSION['username'] = $newUsername;
            $success_message = "Your profile has been updated!";
        } else {
            $error_message = "Failed to update profile.";
        }

        $stmt->close();
    }
}

$conn->close();

?>