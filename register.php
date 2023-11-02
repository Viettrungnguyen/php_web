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

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the registration form submission
    if (isset($_POST['register'])) {
        $input_username = $_POST['username'];
        $input_password = $_POST['password'];
        $user_role = $_POST['role']; // Get the selected user role

        // Check if the username is already in use
        $check_query = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $input_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username already in use.";
        } else {
            // If the username is available, register the user
            $hashed_password = password_hash($input_password, PASSWORD_BCRYPT); // Hash the password

            $insert_query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sss", $input_username, $hashed_password, $user_role); // Store the selected role
            $stmt->execute();

            $user_id = $stmt->insert_id;

            // Set the user's session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_role'] = $user_role; // Store the user's role in the session

            header("Location: login.php");
            exit();
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Register</h1>

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

        <!-- Dropdown to select user role -->
        <label for="role">Role:</label>
        <select id="role" name="role" class="input w-100">
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
            <option value="customer">Customer</option>
        </select>

        <input type="submit" name="register" value="Register">
    </form>

    <p>Already have an account? <a class="link inline-link" href="login.php">Login</a></p>
</body>

</html>