<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT
    orders.id AS order_id,
    orders.order_date,
    orders.status,
    users.username AS customer_name,
    SUM(order_items.quantity * products.price) AS total_amount
FROM orders
INNER JOIN users ON orders.user_id = users.id
INNER JOIN order_items ON orders.id = order_items.order_id
INNER JOIN products ON order_items.product_id = products.id
WHERE orders.user_id = $user_id
GROUP BY orders.id
ORDER BY orders.order_date DESC";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>

<head>
    <title>List of Orders</title>
    <link rel="stylesheet" type="text/css" href="stylePage.css">
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>

    <main class="container">

        <h1>List of Orders</h1>
        <!-- Create an HTML table to display the order data -->
        <table class="data-table">
            <tr>
                <th class="table-header">Order ID</th>
                <th class="table-header">Total Amount</th>
                <th class="table-header">Order Date</th>
                <th class="table-header">Status</th>
                <th class="table-header">Action</th>
            </tr>

            <?php
            // Loop through query results and populate the table
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                    <td class="table-data">' . $row['order_id'] . '</td>
                    <td class="table-data">' . number_format($row['total_amount'], 0, ',', ',') . ' đ' . '</td>
                    <td class="table-data">' . $row['order_date'] . '</td>
                    <td class="table-data">' . $row['status'] . '</td>
                    <td class="table-data">
                        <div class="btn-view">
                            <a href="order_detail-items.php?order_id=' . $row['order_id'] . '">View</a>
                        </div>
                    </td>
                  </tr>';
            }
            ?>

        </table>

        <?php
        // Close the database connection
        $conn->close();
        ?>
    </main>
    <?php include 'footer.php'; ?>

</body>

</html>