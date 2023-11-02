<?php
// Start a session if not already started
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $quantity = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    echo 'Product added to cart!';
} else {
    echo 'Invalid request';
}
