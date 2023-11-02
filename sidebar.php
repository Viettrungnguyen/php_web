<div class="sidebar">
    <ul>
        <?php
        // session_start();
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            echo '<li><a href="categories.php">Categories</a></li>
        <li><a href="products.php">Products</a></li>';
        }
        ?>

        <li><a href="orders.php">Orders</a></li>
    </ul>
</div>