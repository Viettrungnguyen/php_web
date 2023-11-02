<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['category'])) {
    $selectedCategory = $_GET['category'];
    $sql = "SELECT * FROM products WHERE category_id = '" . $selectedCategory . "'";
    $getNameCategorySql = "SELECT name FROM categories WHERE id = '" . $selectedCategory . "'";
    $getName = $conn->query($getNameCategorySql)->fetch_assoc();
} else {
    $sql = "SELECT * FROM products";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    if (isset($_GET['category'])) {
        echo '<h1 style="width: 100%;">All Product of category: ' . $getName['name'] . '</h1>';
    }
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="product">
            <a href="product_detail.php?product_id=' . $row['id'] . '">
                <img class="product-image" src="' . $row['image'] . '" alt="' . $row['name'] . '">
            </a>
            <h2 class="product-title">
                <a class="product-title" href="product_detail.php?product_id=' . $row['id'] . '">' . substr($row['name'], 0, 20) . '</a>
            </h2>
            <p class="product-description">' . substr($row['description'], 0, 50) . '</p>
            <div class="product-price-add">
                <p class="product-price">Price: ' . number_format($row['price'], 0, ',', ',') . ' đ' . '</p>
                <button type="button" onclick="addToCart(' . $row['id'] . ', 1)">Add to Cart</button>
            </div>
        </div>';
    }
} else {
    echo "No products available.";
}


$conn->close();
