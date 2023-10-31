<!DOCTYPE html>
<html>

<head>
    <title>Orders List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="header">
            <h2 class="section-title">Orders List</h2>
        </div>

        <!-- Orders listing table -->
        <table class="data-table">
            <tr>
                <th class="table-header">Order ID</th>
                <th class="table-header">Customer Name</th>
                <th class="table-header">Total Amount</th>
                <th class="table-header">Order Date</th>
            </tr>

            <?php
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "web_sell_clother";

            $conn = new mysqli($host, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch orders from the database
            $sql = "SELECT * FROM orders";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='table-data'>" . $row['order_id'] . "</td>";
                    echo "<td class='table-data'>" . $row['customer_name'] . "</td>";
                    echo "<td class='table-data'>$" . $row['total_amount'] . "</td>";
                    echo "<td class='table-data'>" . $row['order_date'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='table-no-data table-data'>No orders found.</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>

</html>