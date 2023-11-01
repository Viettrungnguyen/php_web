<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"])) {
    $order_id = $_POST["order_id"];

    // Handle order deletion
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // Redirect to a page or show a confirmation message
        header("Location: orders.php");
        exit;
    }

    // Close the database connection
    $conn->close();

    echo "Order deleted successfully";
} else {
    echo "Invalid request";
}
