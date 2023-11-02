<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateCategory"])) {
    // Get the category ID and updated name from the form
    $categoryId = $_POST["category_id"];
    $updatedCategoryName = $_POST["category_name"];

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "web_sell_clother";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Assuming your categories table has columns 'id' and 'name'
    $sql = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $updatedCategoryName, $categoryId);
        if ($stmt->execute()) {
            header("Location: categories.php");
            exit;
        } else {
            echo "Error updating category: " . $stmt->error;
        }
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
