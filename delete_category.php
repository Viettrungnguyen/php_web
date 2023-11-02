<?php
$category_id = $_GET['id'];

$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

if (isset($_GET['id'])) {

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $category_id);

        if ($stmt->execute()) {
            header("Location: categories.php");
            exit;
        } else {
            echo "Error deleting category: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
}

$conn->close();
