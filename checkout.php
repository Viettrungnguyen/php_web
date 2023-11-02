<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cartData = $_SESSION['cart'];

$cartDataJson = json_encode($cartData);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="stylePage.css">
    <style>
        /* Add this CSS to your stylePage.css or in a separate stylesheet */

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .product-table th {
            background-color: #f2f2f2;
        }

        .product-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .product-table tr:hover {
            background-color: #ddd;
        }

        .product-cart {
            display: flex;
            align-items: center;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .product-cart img {
            max-width: 100px;
            max-height: 100px;
            margin-right: 10px;
        }

        .product-description {
            flex: 1;
        }

        .product-cart button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        .product-cart button:hover {
            background-color: #45a049;
        }

        .product-cart button[type="submit"] {
            padding: 8px;
            margin-left: 5px;
            border-radius: 5px;
        }

        .product-cart input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 60px;
        }

        .product-cart input[type="number"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.7);
        }

        .product-cart {
            background: none;
            border: none;
            padding: 0;
            width: fit-content;
            margin: 0;
        }


        /* Style the form header */
        .form-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Style form fields and labels */
        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-input {
            width: 300px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Style the buttons */
        .form-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>
    <main>
        <h1>Checkout</h1>
        <form id="checkout-form" action="process_order.php" method="post">
            <label for="phone" class="form-label">Phone:</label>
            <input type="text" id="phone" name="phone" required class="form-input">

            <label for="address" class="form-label">Address:</label>
            <textarea id="address" name="address" required class="form-input" rows="4"></textarea>

            <h1>All Product Will Buy</h1>
            <?php
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                // Connect to the database
                $host = "localhost";
                $username = "root";
                $password = "";
                $database = "web_sell_clother";

                $conn = new mysqli($host, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get product details based on the product IDs in the cart
                $total = 0;
                $product_ids = array_keys($_SESSION['cart']);
                $product_ids_string = implode(",", $product_ids);
                $sql = "SELECT * FROM products WHERE id IN ($product_ids_string)";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="product-table">';
                    echo '<tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>';

                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['id'];
                        $product_name = $row['name'];
                        $product_price = $row['price'];
                        $quantity = $_SESSION['cart'][$product_id];

                        $maxNameLength = 20;
                        $maxDescriptionLength = 50;
                        $subtotal = $product_price * $quantity;
                        $total += $subtotal;
                        $product_name = strlen($product_name) > $maxNameLength ? substr($product_name, 0, $maxNameLength) . '...' : $product_name;

                        echo '<tr>
                        <td><a href="product_detail.php?product_id=' . $product_id . '"><img src="' . $row['image'] . '" alt="' . $product_name . '" style="max-width: 100px; max-height: 100px;"></a></td>
                        <td><a href="product_detail.php?product_id=' . $product_id . '">' . $product_name . '</a></td>
                        <td>'  . number_format($product_price, 0, ',', ',') . ' đ' . '</td>
                        <td>' .  $quantity  . '</td>
                        </tr>';
                    }
                    echo '<tr>
                <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>' . number_format($total, 0, ',', ',') . ' đ'  . '</td>
                </tr>';

                    echo '</table>';
                } else {
                    echo "Your cart is empty.";
                }

                $conn->close();
            } else {
                echo "Your cart is empty.";
            }
            ?>
            <div class="box-button">
                <a href="cart.php" class="button button-back">Back to cart</a>
                <button type="submit" class="form-button button button-right">Place Order</button>
            </div>
        </form>
    </main>
</body>


</html>