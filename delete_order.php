<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];

        // Prepare and execute a SQL DELETE statement
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $order_id);
            if ($stmt->execute()) {
                echo "Order deleted successfully.";
            } else {
                echo "Error deleting order: " . $stmt->error;
            }
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
