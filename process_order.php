<?php
session_start();

if (isset($_POST['phone']) && isset($_POST['address'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $status = 'pending'; // You can set the initial status as needed
    $order_date = time(); // Get the current Unix timestamp

    // Connect to the database
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "web_sell_clother";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $insertOrderSQL = "INSERT INTO orders (user_id, order_date, status, phone, address)
                      VALUES ('$user_id', FROM_UNIXTIME($order_date), '$status', '$phone', '$address')";

    if ($conn->query($insertOrderSQL) === TRUE) {
        $order_id = $conn->insert_id;
        $cart = $_SESSION['cart'];

        foreach ($cart as $product_id => $quantity) {
            $insertOrderDetailsSQL = "INSERT INTO order_items (order_id, product_id, quantity)
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
