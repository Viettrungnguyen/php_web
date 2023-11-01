<?php
if (isset($_POST['submitCategory'])) {
    $categoryName = $_POST['categoryName'];

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "web_sell_clother";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape and sanitize user input (to prevent SQL injection)
    $categoryName = mysqli_real_escape_string($conn, $categoryName);

    // Insert the new category into the database
    $sql = "INSERT INTO categories (name) VALUES ('$categoryName')";
    $result = $conn->query($sql);

    if ($result) {
        // Category insertion was successful
        // Redirect back to the list_categories page to display the updated list
        header("Location: categories.php");
        exit;
    } else {
        // Category insertion failed
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
