<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search form is submitted
if (isset($_POST['search_orders'])) {
    // Get the search input
    $search = $_POST['search'];

    // Write SQL query to retrieve filtered orders data based on the search input
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
            WHERE
                orders.id LIKE '%" . $search . "%'
                OR users.username LIKE '%" . $search . "%'
                OR orders.status LIKE '%" . $search . "%'
            GROUP BY orders.id
            ORDER BY orders.order_date DESC";
} else {
    // Write SQL query to retrieve all orders data
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
            GROUP BY orders.id
            ORDER BY orders.order_date DESC";
}

// Execute the SQL query
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>

<head>
    <title>List of Orders</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="content">

        <h1>List of Orders</h1>
        <form method="post" action="orders.php" class="form-order">
            <input type="text" id="search" class="search-order" name="search" placeholder="Search by Order ID, Customer Name, or Status">
            <input type="submit" name="search_orders" value="Search">
        </form>


        <!-- Create an HTML table to display the order data -->
        <table class="data-table">
            <tr>
                <th class="table-header">Order ID</th>
                <th class="table-header">Customer Name</th>
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
                    <td class="table-data">' . $row['customer_name'] . '</td>
                    <td class="table-data">' . number_format($row['total_amount'], 0, ',', ',') . ' đ' . '</td>
                    <td class="table-data">' . $row['order_date'] . '</td>
                    <td class="table-data">' . $row['status'] . '</td>
                    <td class="table-data">
                        <div class="btn-view">
                            <a href="view_order.php?order_id=' . $row['order_id'] . '">View</a>
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
    </div>
</body>

</html>