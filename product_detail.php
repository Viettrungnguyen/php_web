<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID not specified in the URL.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" type="text/css" href="stylePage.css">
    <style>
        .product-detail-container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .product-detail-image {
            max-width: 40%;
            max-height: 300px;
            margin-right: 20px;
        }

        .product-detail-info {
            flex: 1;
        }

        .product-detail-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-detail-description {
            margin-bottom: 20px;
        }

        .product-detail-price {
            font-size: 20px;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .product-detail-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        .product-detail-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>
    <main>
        <div class="product-detail-container">
            <img class="product-detail-image" src="<?php echo $product['image'] ?>" alt="<?php echo $product['name']; ?>">
            <div class="product-detail-info">
                <h2 class="product-detail-title"><?php echo $product['name']; ?></h2>
                <p class="product-detail-description"><?php echo $product['description']; ?></p>
                <p class="product-detail-price">Price: <?php echo number_format($product['price'], 0, ',', ','); ?> đ</p>
                <button class="product-detail-button" type="button" onclick="addToCart(<?php echo $product['id']; ?>, 1)">Add to Cart</button>
            </div>
        </div>
    </main>
</body>

</html>