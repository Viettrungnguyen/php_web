<?php
session_start();

if (isset($_POST['phone']) && isset($_POST['address'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $status = 'pending';
    $order_date = date('Y-m-d H:i:s');

    // Connect to the database
    $host = "localhost";
    $username = "root";
    $password = "root";
    $database = "web_sell_clother";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the order into the orders table
    $insertOrderSQL = "INSERT INTO orders (user_id, order_date, status, phone, address, total)
                      VALUES ('$user_id', '$order_date', '$status', '$phone', '$address', '$total')";

    if ($conn->query($insertOrderSQL) === TRUE) {
        $order_id = $conn->insert_id;
        $cart = $_SESSION['cart'];

        foreach ($cart as $product_id => $quantity) {
            $insertOrderDetailsSQL = "INSERT INTO order_details (order_id, product_id, quantity)
                                      VALUES ('$order_id', '$product_id', '$quantity')";
            $conn->query($insertOrderDetailsSQL);
        }

        unset($_SESSION['cart']);

        header("Location: success_page.php");
    } else {
        echo "Error: " . $insertOrderSQL . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    header("Location: checkout.php?error=1");
}
