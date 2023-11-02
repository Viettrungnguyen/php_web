<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Store</title>
    <link rel="stylesheet" type="text/css" href="stylePage.css">
</head>

<body>
    <?php include 'navbarHomePage.php'; ?>
    <main>
        <div class="product-container" id="productContainer">
            <?php
            include('get_products.php');
            ?>
        </div>
    </main>
</body>
<script>
    function addToCart(productId, quantity) {
        var xhr = new XMLHttpRequest();
        var url = 'add_to_cart.php';
        var data = 'product_id=' + productId + '&quantity=' + quantity;

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Product added to cart!');
            } else {
                alert('Error adding product to cart');
            }
        };

        xhr.send(data);
    }
</script>

</html>