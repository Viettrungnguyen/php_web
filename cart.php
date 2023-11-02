<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Detail</title>
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


        /* The modal background */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        /* Modal content */
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 30%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-button {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal-button.cancel {
            background-color: #4CAF50;
        }

        .modal-button.delete {
            background-color: #ff4136 !important;
        }
    </style>
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>
    <main class="container" >
        <h1>Cart Detail</h1>
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
                        <th>Action</th>
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
                        <td>
                            <form method="post" action="update_cart.php" class="product-cart">
                                <input type="hidden" name="product_id" value="' . $product_id . '">
                                <input type="number" name="quantity" value="' . $quantity . '">
                                <button type="submit" name="update_cart">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" class="product-cart">
                                <button class="btn-delete" type="submit" data-product-id="' . $product_id . '" name="remove_from_cart">Delete</button>
                            </form>
                        </td>
                        </tr>';
                }
                echo '<tr>
                <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>' . number_format($total, 0, ',', ',') . ' đ'  . '</td>
                    <td></td>
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
            <a href="homepage.php" class="button button-back">Back to Homepage</a>
            <a href="checkout.php" class="button button-right">Proceed to Checkout</a>
        </div>
    </main>
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to remove this item from your cart?</p>
            <div class="modal-buttons">
                <button id="cancelBtn" class="modal-button cancel">Cancel</button>
                <button id="confirmDeleteBtn" class="modal-button delete">Confirm Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteModal = document.getElementById("deleteModal");
            const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
            const cancelBtn = document.getElementById("cancelBtn");

            let productIdToDelete = null;

            document.querySelectorAll('.btn-delete').forEach((deleteButton) => {
                deleteButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    productIdToDelete = event.currentTarget.getAttribute("data-product-id");
                    deleteModal.style.display = "block";
                });
            });

            confirmDeleteBtn.addEventListener("click", function() {
                if (productIdToDelete !== null) {
                    window.location.href = 'delete_from_cart.php?product_id=' + productIdToDelete;
                }

                deleteModal.style.display = "none";
            });

            cancelBtn.addEventListener("click", function() {
                productIdToDelete = null;
                deleteModal.style.display = "none";
            });
        });
    </script>

</body>

</html>