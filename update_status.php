<?php
if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $host = "localhost";
    $username = "root";
    $password = "root";
    $database = "web_sell_clother";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            echo "Status updated successfully.";
        } else {
            echo "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}
