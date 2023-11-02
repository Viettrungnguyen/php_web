<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $userLoggedIn = true;

    if (isset($_SESSION['user_role'])) {
        $userRole = $_SESSION['user_role'];
    } else {
        $userRole = 'customer';
    }
} else {
    $userLoggedIn = false;
    $userRole = 'customer';
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    #cartIcon {
        position: relative;
    }

    #cartItemCount {
        position: absolute;
        top: -12px;
        right: -10px;
        background-color: #FF5722;
        color: #fff;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 11px;
        text-align: center;
        line-height: 16px;
    }
</style>
<header>
    <div class="navbar">
        <div class="navbar-left">
            <div class="logo">

            </div>
            <div class="logo">
                <a href="homepage.php">
                    <img src="logo.png" alt="Logo" height="52px">
                </a>
            </div>
            <div class="navbar-right">
                <div class="user-dropdown">
                    <div class="user-icon">
                        <i class="fa-solid fa-user fa-xl" id="userIcon" style="color: #ffffff;"></i>
                    </div>
                    <div class="dropdown-content" id="userDropdown">
                        <?php
                        if (!$userLoggedIn || $userRole !== 'customer') {
                            echo '<a href="login.php">Login</a>';
                        }
                        ?>

                        <?php
                        if ($userRole === 'customer' && $userLoggedIn) {
                            echo '<a href="profile_user.php">My Profile</a>';
                            echo '<a href="orderlist.php">My Orders</a>';
                            echo '<a href="logout.php">Logout</a>';
                        }
                        ?>
                    </div>
                </div>

                <a href="cart.php">
                    <div class="cart-icon" id="cartIcon">
                        <i class="fa-solid fa-cart-shopping fa-xl" style="color: #ffffff;"></i>
                        <div class="cart-contents" id="cartContents">
                            <?php
                            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                $totalQuantity = array_sum($_SESSION['cart']);
                                echo '<span class="cart-quantity" id="cartItemCount">' . $totalQuantity . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="navbar-center-item">
            <div class="navbar-item">
                <a href="homepage.php">Home</a>
            </div>
            <div class="navbar-item">
                <a href="javascript:void(0);">Categories</a>
                <div class="dropdown-cate" id="categoryDropdown">
                </div>
            </div>
            <div class="navbar-item">
                <a href="about_us.php">About Us</a>
            </div>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search for products..." onkeyup="searchProducts()">

            </div>
        </div>

    </div>
</header>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: 'get_categories.php',
            type: 'GET',
            dataType: 'json',
            success: function(categories) {
                var dropdownContent = $('#categoryDropdown');
                categories.forEach(function(category) {
                    var categoryUrl = 'homepage.php?category=' + category.id;
                    dropdownContent.append('<a href="' + categoryUrl + '">' + category.name + '</a>');
                });
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var userIcon = document.getElementById("userIcon");
        var userDropdown = document.getElementById("userDropdown");

        userIcon.addEventListener("click", function() {
            if (userDropdown.style.display === "block") {
                userDropdown.style.display = "none";
            } else {
                userDropdown.style.display = "block";
            }
        });

        window.addEventListener("click", function(event) {
            if (event.target !== userIcon && event.target !== userDropdown) {
                userDropdown.style.display = "none";
            }
        });
    });


    function searchProducts() {
        var input, filter, productContainer, product, title, description, i;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        productContainer = document.getElementById("productContainer");
        product = productContainer.getElementsByClassName("product");

        for (i = 0; i < product.length; i++) {
            title = product[i].getElementsByClassName("product-title")[0];
            description = product[i].getElementsByClassName("product-description")[0];

            if (title.textContent.toUpperCase().indexOf(filter) > -1 || description.textContent.toUpperCase().indexOf(filter) > -1) {
                product[i].style.display = "";
            } else {
                product[i].style.display = "none";
            }
        }
    }
</script>