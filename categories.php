<?php
session_start();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header("Location: permission_failed.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>List Categories</title>
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
            padding: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
        }

        .close {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 20px;
            cursor: pointer;
        }
    </style>

</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1>List of Categories</h1>

        <div class="btn-create">
            <a href='javascript:void(0);' onclick='createCategoryButton()' id="createCategoryButton" class="btn-create">Create Category</a>
            <!-- <button id="createCategoryButton">Create Category</button> -->

        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th class="table-header">Category ID</th>
                    <th class="table-header">Category Name</th>
                    <th class="table-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $host = "localhost";
                $username = "root";
                $password = "";
                $database = "web_sell_clother";

                $conn = new mysqli($host, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, name FROM categories";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $categories = array();

                    while ($row = $result->fetch_assoc()) {
                        $categories[] = $row;
                    }
                } else {
                    $categories = array(); // No categories found
                }

                foreach ($categories as $category) {
                    echo "<tr>";
                    echo "<td class='table-data'>{$category['id']}</td>";
                    echo "<td class='table-data'>{$category['name']}</td>";

                    echo "<td class='table-data table-action'>
                        <a href='javascript:void(0);' onclick=\"openEditModal({$category['id']}, '{$category['name']}')\" class='btn-edit edit'>Edit</a>
                        <a href='javascript:void(0);' onclick=\"openDeleteConfirmation({$category['id']})\" class='btn-delete delete'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div id="createCategoryModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCreateCategoryModal">&times;</span>
            <h2>Create Category</h2>
            <form action="create_category.php" method="post">
                <label for="categoryName">Category Name:</label>
                <input type="text" id="categoryName" name="categoryName" required>
                <input type="submit" name="submitCategory" value="Create">
            </form>
        </div>
    </div>

    <div id="editCategoryModal" class="modal">
        <div id="editCategoryModalContent" class="modal-content">
            <!-- Modal content for editing categories goes here -->
        </div>
    </div>

    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this category?</p>
            <button class="btn" id="confirmDeleteButton">Confirm</button>
            <button class="btn btn-delete" id="cancelDeleteButton">Cancel</button>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function createCategoryButton() {
            const createCategoryModal = document.getElementById("createCategoryModal");
            const createCategoryButton = document.getElementById("createCategoryButton");
            const closeCreateCategoryModal = document.getElementById("closeCreateCategoryModal");

            createCategoryModal.style.display = "block";

            closeCreateCategoryModal.addEventListener("click", () => {
                createCategoryModal.style.display = "none";
            });

            window.addEventListener("click", (event) => {
                if (event.target === createCategoryModal) {
                    createCategoryModal.style.display = "none";
                }
            });
        }

        function openEditModal(categoryId, categoryName) {
            var editCategoryModal = document.getElementById('editCategoryModal');
            var modalContent = document.getElementById('editCategoryModalContent');

            modalContent.innerHTML = `
            <h2>Edit Category</h2>
            <form action="edit_category.php" method="post">
                <input type="hidden" name="category_id" value="${categoryId}">
                <input type="text" name="category_name" value="${categoryName}">
                <button type="submit" name="updateCategory">Save Changes</button>
            </form>
        `;
            window.addEventListener("click", (event) => {
                if (event.target === editCategoryModal) {
                    editCategoryModal.style.display = "none";
                }
            });
            // Show the modal
            editCategoryModal.style.display = 'block';
        }

        // Function to open the delete confirmation modal
        function openDeleteConfirmation(categoryId) {
            const deleteConfirmationModal = document.getElementById("deleteConfirmationModal");
            deleteConfirmationModal.style.display = "block";

            document.getElementById("confirmDeleteButton").addEventListener("click", function() {
                window.location.href = "delete_category.php?id=" + categoryId;
            });

            document.getElementById("cancelDeleteButton").addEventListener("click", function() {
                deleteConfirmationModal.style.display = "none";
            });
            window.addEventListener("click", (event) => {
                if (event.target === deleteConfirmationModal) {
                    deleteConfirmationModal.style.display = "none";
                }
            });
        }

        function closeDeleteConfirmation() {
            const deleteConfirmationModal = document.getElementById("deleteConfirmationModal");
            deleteConfirmationModal.style.display = "none";
        }
    </script>

</body>

</html>