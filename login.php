<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Check the user's role stored in the session
    $user_role = $_SESSION['user_role'];

    // Determine where to redirect based on the user's role
    if ($user_role === 'admin' || $user_role === 'staff') {
        header("Location: dashboard.php");
        exit();
    } elseif ($user_role === 'customer') {
        header("Location: homepage.php");
        exit();
    }
}

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

// Create a database connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the login form submission
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $input_username = $mysqli->real_escape_string($_POST['username']);
        $input_password = $_POST['password'];

        // Query to check if the user exists and retrieve user information including the role
        $query = "SELECT id, username, password, role FROM users WHERE username = '$input_username'";
        $result = $mysqli->query($query);

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($input_password, $user['password'])) {
                // Set the user's session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Determine where to redirect based on the user's role
                if ($user['role'] === 'admin' || $user['role'] === 'staff') {
                    header("Location: dashboard.php");
                } else if ($user['role'] === 'customer') {
                    header("Location: homepage.php");
                }
                exit();
            }
        }

        $error_message = "Invalid username or password.";
    }
}

// Close the database connection
$mysqli->close();
?>



<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Login</h1>
    <?php
    if (isset($error_message)) {
        echo '<p style="color: red;">' . $error_message . '</p>';
    }
    ?>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a class="link inline-link" href="register.php">Register</a></p>

</body>

</html>