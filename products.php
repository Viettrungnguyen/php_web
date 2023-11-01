<!DOCTYPE html>
<html>

<head>
    <title>Product List</title>
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

        .modal-buttons {
            text-align: right;
        }

        /* Search Bar Styles */
        .search-container {
            margin-top: 20px;
            text-align: left;
            width: 300px;
        }

        .search-input {
            padding: 5px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="header">
            <h1>Product List</h1>
            <div class="btn-create">
                <a href="create_product.php" class="btn-create">Create Product</a>
            </div>
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" onkeyup="searchProducts()" placeholder="Search for products...">
            </div>
        </div>

        <!-- Product listing table -->
        <table class="data-table">
            <tr>
                <th class="table-header">Image</th>
                <th class="table-header">Name</th>
                <th class="table-header">Category</th>
                <th class="table-header">Price</th>
                <th class="table-header">Description</th>
                <th class="table-header">Action</th>
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

            // Define a JavaScript function to show the confirmation modal
            echo '<script>
                function showConfirmationModal(productID) {
                    var modal = document.getElementById("deleteConfirmationModal");
                    modal.style.display = "block";

                    var confirmBtn = document.getElementById("confirmDeleteButton");
                    confirmBtn.onclick = function() {
                        window.location.href = "?delete_product_id=" + productID;
                    };

                    // Add event listener to close modal when clicking outside
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            closeConfirmationModal();
                        }
                    };
                }

                function closeConfirmationModal() {
                    var modal = document.getElementById("deleteConfirmationModal");
                    modal.style.display = "none";
                }

                function searchProducts() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("searchInput");
                    filter = input.value.toUpperCase();
                    table = document.querySelector(".data-table");
                    tr = table.getElementsByTagName("tr");

                    for (i = 1; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[1]; // Column index for product name
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }
            </script>';

            // Fetch products with category names from the database
            $sql = "SELECT products.*, categories.name AS category_name
            FROM products
            INNER JOIN categories ON products.category_id = categories.id;";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='table-data'><img src='" . $row['image'] . "' alt='" . $row['name'] . "' class='product-image'></td>";
                    echo "<td class='table-data'>" . $row['name'] . "</td>";
                    echo "<td class='table-data'>" . $row['category_name'] . "</td>";
                    echo "<td class='table-data'>" . number_format($row['price'], 0, ',', '.') . ' đ' . "</td>";
                    echo "<td class='table-data'>" . (strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description']) . "</td>";
                    echo "<td class='table-data table-action'>
                            <a href='edit_product.php?product_id=" . $row['id'] . "' class='btn-edit edit'>Edit</a>
                            <a href='javascript:void(0);' onclick='showConfirmationModal(" . $row['id'] . ")' class='btn-delete delete'>Delete</a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='table-data table-no-data'>No product found.</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
    <!-- Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this product?</p>
            <div class="modal-buttons">
                <button id="confirmDeleteButton">Confirm</button>
                <button onclick="closeConfirmationModal()">Cancel</button>
            </div>
        </div>
    </div>

</body>

</html>

<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];

    // Prepare and execute a SQL DELETE statement
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            echo '<script>window.location.href = "products.php";</script>';
            exit; // Make sure to exit to prevent further script execution
        } else {
            echo "Error deleting product: " . $stmt->error;
        }
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>