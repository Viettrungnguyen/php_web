<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["categoryId"])) {
    $categoryId = $_POST["categoryId"];

    $host = "localhost";
    $username = "root";
    $password = "root";
    $database = "web_sell_clother";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name FROM categories WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $categoryId);

        if ($stmt->execute()) {
            $stmt->bind_result($categoryName);
            $stmt->fetch();
            $stmt->close();
            $conn->close();

            echo json_encode(array("name" => $categoryName));
        } else {
            echo json_encode(array("error" => "Error executing the query"));
        }
    } else {
        echo json_encode(array("error" => "Error preparing the query"));
    }
} else {
    // Handle invalid requests or errors
    echo json_encode(array("error" => "Invalid request"));
}
