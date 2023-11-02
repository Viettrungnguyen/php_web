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
    <title>Create Product</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h2 class="section-title">Create Product</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="name" class="label">Product Name:</label>
            <input type="text" id="name" name="name" class="input" required>

            <label for="price" class="label">Product Price:</label>
            <input type="number" id="price" name="price" class="input" required>

            <label for="description" class="label">Product Description:</label>
            <textarea id="description" name="description" class="input" required></textarea>

            <label for="category" class="label">Category:</label>
            <select id="category" name="category" class="input w-100" required>
                <?php
                // Connect to the database and fetch categories
                $host = "localhost";
                $username = "root";
                $password = "root";
                $database = "web_sell_clother";

                $conn = new mysqli($host, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, name FROM categories";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }

                $conn->close();
                ?>
            </select>

            <label for="image" class="label">Product Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit" name="submit" class="btn btn-submit">Create Product</button>
        </form>
    </div>
</body>

</html>

<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Image upload
    $target_directory = "uploads/products/";
    $image = $target_directory . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
        echo "Invalid image file.";
    } elseif (file_exists($image)) {
        echo "File already exists.";
    } elseif ($_FILES["image"]["size"] > 500000) {
        echo "File is too large.";
    } elseif (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
    ) {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
    } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
        // Image upload successful, insert data into the database
        $sql = "INSERT INTO products (name, price, description, image, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sdssi", $name, $price, $description, $image, $category);
            if ($stmt->execute()) {
                echo "Product added successfully.";
            } else {
                echo "Error adding product: " . $stmt->error;
            }
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading image.";
    }
}

$conn->close();
?>