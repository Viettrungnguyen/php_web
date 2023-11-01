<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Retrieve order details
    $sql = "SELECT
                orders.id AS order_id,
                orders.order_date,
                orders.status,
                users.username AS customer_name
            FROM orders
            INNER JOIN users ON orders.user_id = users.id
            WHERE orders.id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if (!$order) {
            // Order not found
            echo "Order not found.";
            $conn->close();
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Order Details</h1>
        <div class="control-order">
            <div class="info-order">
                <h2>Order ID: <?php echo $order['order_id']; ?></h2>
                <p>Customer Name: <?php echo $order['customer_name']; ?></p>
                <p>Order Date: <?php echo $order['order_date']; ?></p>
                <p>Status: <?php echo $order['status']; ?></p>
            </div>
            <div>
                <?php
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                    echo '<div class="border-admin-action-box">';
                    echo '<h3>Admin Options</h3>';
                    echo '<div class="action-order-admin">';
                    echo '<select id="statusDropdown" name="new_status">
                            <option value="pending" ' . ($order['status'] === 'pending' ? 'selected' : '') . '>Pending</option>
                            <option value="completed" ' . ($order['status'] === 'completed' ? 'selected' : '') . '>Completed</option>
                            <option value="canceled" ' . ($order['status'] === 'canceled' ? 'selected' : '') . '>Canceled</option>
                        </select>';

                    echo '<form class="delete-order-form" action="" method="post">';
                    echo '<input type="submit" class="btn-delete" name="delete_order" value="Delete Order">';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>


        <!-- Display order items in a table -->
        <h3>Order Items</h3>
        <table class="data-table">
            <tr>
                <th class="table-header">Product Name</th>
                <th class="table-header">Quantity</th>
                <th class="table-header">Price</th>
                <th class="table-header">Total</th>
            </tr>
            <?php
            // Retrieve and display order items
            $sql = "SELECT
                    products.name AS product_name,
                    order_items.quantity,
                    products.price,
                    (order_items.quantity * products.price) AS total
                FROM order_items
                INNER JOIN products ON order_items.product_id = products.id
                WHERE order_items.order_id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $order_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($item = $result->fetch_assoc()) {
                    echo '<tr>
                        <td class="table-data">' . $item['product_name'] . '</td>
                        <td class="table-data">' . $item['quantity'] . '</td>
                        <td class="table-data">' . number_format($item['price'], 0, ',', '.') . ' đ' . '</td>
                        <td class="table-data">' . number_format($item['total'], 0, ',', '.') . ' đ' . '</td>
                      </tr>';
                }
            }
            ?>
        </table>

        <?php
        // Close the prepared statement
        $stmt->close();
        ?>

    </div>
</body>
<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var statusDropdown = $("#statusDropdown");
        var statusMessage = $("#statusMessage");

        statusDropdown.on("change", function() {
            var newStatus = statusDropdown.val();
            $.ajax({
                url: "update_status.php",
                type: "POST",
                data: {
                    order_id: <?php echo $order_id; ?>,
                    new_status: newStatus
                },
                success: function(response) {
                    location.reload(); // Reload the page
                },
                error: function(xhr, status, error) {
                    // Fail
                }
            });
        });
    });
</script>


</html>

<?php
if (isset($_POST['change_status'])) {
    // Handle status change
    $new_status = $_POST['new_status'];

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
    }
}

if (isset($_POST['delete_order'])) {
    // Handle order deletion
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // Redirect to a page or show a confirmation message
        header("Location: orders.php");
        exit;
    }
}

// Close the database connection
$conn->close();
?>