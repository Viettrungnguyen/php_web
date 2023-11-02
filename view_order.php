<?php
$host = "localhost";
$username = "root";
$password = "root";
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
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .modal-content {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
        }

        .modal button {
            padding: 10px 20px;
            margin-right: 10px;
        }

        .modal-content p {
            margin-bottom: 10px;
        }
    </style>
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
                    echo '<select id="statusDropdown" name="new_status" class="w-100">
                            <option value="pending" ' . ($order['status'] === 'pending' ? 'selected' : '') . '>Pending</option>
                            <option value="completed" ' . ($order['status'] === 'completed' ? 'selected' : '') . '>Completed</option>
                            <option value="canceled" ' . ($order['status'] === 'canceled' ? 'selected' : '') . '>Canceled</option>
                        </select>';

                    echo '<button id="deleteButton" class="btn btn-delete">Delete Order</button>';
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

        <!-- Add this section below your existing HTML code -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <h3>Confirm Deletion</h3>
                <p>Are you sure you want to delete this order?</p>
                <button class="btn" id="cancelButton">Cancel</button>
                <button class="btn btn-delete" id="confirmButton">Delete</button>
            </div>
        </div>

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
<script>
    const confirmationModal = document.getElementById('confirmationModal');
    const cancelButton = document.getElementById('cancelButton');
    const confirmButton = document.getElementById('confirmButton');

    // Function to show the confirmation modal
    function showConfirmationModal() {
        confirmationModal.style.display = 'block';
    }

    // Function to close the confirmation modal
    function closeConfirmationModal() {
        confirmationModal.style.display = 'none';
    }

    // When the delete button is clicked, show the confirmation modal
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.addEventListener('click', showConfirmationModal);

    // When the cancel button is clicked, close the confirmation modal
    cancelButton.addEventListener('click', closeConfirmationModal);

    confirmButton.addEventListener('click', function() {
        // Send an AJAX request to delete_order.php
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_order.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // The order ID to delete
        const orderID = <?php echo $order_id; ?>;

        // Create a data string to send
        const data = 'order_id=' + orderID;

        // Handle the response from the server
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText === 'Order deleted successfully.') {
                // If the order is deleted successfully, redirect to the orders page.
                window.location.href = 'orders.php';
            }
        };

        // Send the AJAX request
        xhr.send(data);
    });

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === confirmationModal) {
            closeConfirmationModal();
        }
    };
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

// Close the database connection
$conn->close();
?>