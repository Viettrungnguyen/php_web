<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h2 class="section-title">Edit Product</h2>

        <?php
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "web_sell_clother";

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve product data based on the product ID from the URL parameter
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                if ($product) {
                    echo '<form action="" method="post" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="id" value="' . $product['id'] . '">';
                    echo '<label for="name" class="label">Product Name:</label>';
                    echo '<input type="text" id="name" name="name" class="input" required value="' . $product['name'] . '">';
                    echo '<label for="price" class="label">Product Price:</label>';
                    echo '<input type="number" id="price" name="price" class="input" required value="' . $product['price'] . '">';
                    echo '<label for="description" class="label">Product Description:</label>';
                    echo '<textarea id="description" name="description" class="input" required>' . $product['description'] . '</textarea>';

                    // Fetch categories from the database
                    $sql = "SELECT id, name FROM categories";
                    $result = $conn->query($sql);

                    echo '<label for="category" class="label">Category:</label>';
                    echo '<select id="category" name="category" class="input w-100" required>';
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $product['category_id']) ? 'selected' : '';
                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                    }
                    echo '</select>';

                    echo '<label class="label">Existing Product Image:</label>';
                    echo '<img src="' . $product['image'] . '" class="existing-image" alt="Existing Image">';
                    echo '<label for="image" class="label mt-10px">New Product Image:</label>';
                    echo '<input type="file" id="image" name="image" accept="image/*">';
                    echo '<button type="submit" name="submit" class="btn btn-submit">Update Product</button>';
                    echo '</form>';
                } else {
                    echo 'Product not found.';
                }
            }
        }

        $conn->close();
        ?>
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

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Check if a new image is uploaded
    if ($_FILES["image"]["name"]) {
        $target_directory = "uploads/products/";
        $target_file = $target_directory . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
            echo "Invalid image file.";
        } elseif (file_exists($target_file)) {
            echo "File already exists.";
        } elseif ($_FILES["image"]["size"] > 500000) {
            echo "File is too large.";
        } elseif (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
        ) {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update product data in the database, including the new image path
            $sql = "UPDATE products SET name = ?, price = ?, description = ?, image = ?, category_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sdsisi", $name, $price, $description, $target_file, $category, $id);
                if ($stmt->execute()) {
                    echo "Product updated successfully with a new image.";
                } else {
                    echo "Error updating product: " . $stmt->error;
                }
            } else {
                echo "Error preparing SQL statement: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading image.";
        }
    } else {
        // No new image provided, update product data without changing the image
        $sql = "UPDATE products SET name = ?, price = ?, description = ?, category_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sdsii", $name, $price, $description, $category, $id);
            if ($stmt->execute()) {
                echo "Product updated successfully.";
            } else {
                echo "Error updating product: " . $stmt->error;
            }
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>