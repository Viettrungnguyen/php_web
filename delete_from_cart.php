<?php
session_start();

if (isset($_GET['product_id'])) {
    $productIdToDelete = $_GET['product_id'];

    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$productIdToDelete])) {
        unset($_SESSION['cart'][$productIdToDelete]);
    }

    header('Location: cart.php');
    exit();
}
