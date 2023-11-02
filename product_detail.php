<?php
$host = "localhost";
$username = "root";
$password = "";
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
        main {
            min-height: 67vh;

        }

        .product-detail-container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
        }

        .product-detail-image {
            max-width: 40%;
            max-height: 300px;
            margin-right: 20px;
        }

        .product-detail-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            color: black;
            margin-bottom: 10px;
        }

        .product-detail-button {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            margin-left: auto;
        }

        .product-detail-button:hover {
            background-color: #3a3a3a;
        }
    </style>
</head>

<body>
    <?php include 'navbarHomepage.php'; ?>
    <main>
        <div class="product-detail-container">
            <img class="product-detail-image" src="<?php echo $product['image'] ?>" alt="<?php echo $product['name']; ?>">
            <div class="product-detail-info">
                <div>
                    <h2 class="product-detail-title"><?php echo $product['name']; ?></h2>
                    <p class="product-detail-description"><?php echo $product['description']; ?></p>
                </div>
                <div class="product-flex-end">
                    <p class="product-detail-price">Price: <?php echo number_format($product['price'], 0, ',', ','); ?> đ</p>
                    <button class="product-detail-button" type="button" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>

</body>

<script>
    function addToCart(productId, quantity) {
        // Prepare the data to send to the server
        const data = {
            product_id: productId,
        };

        // Send an AJAX request to the server
        $.ajax({
            type: "POST",
            url: "add_to_cart.php", // Replace with the actual URL to handle cart updates
            data: data,
            success: function(response) {
                // Handle the server response, e.g., update the cart icon or show a confirmation message
                alert("Product added to cart!");
            },
            error: function(error) {
                // Handle any errors, e.g., show an error message
                console.error("Error adding product to cart: " + error);
            }
        });
    }
</script>

</html>