<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web_sell_clother";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['query'])) {
    $searchQuery = $_POST['query'];
    $sql = "SELECT * FROM products WHERE name LIKE '%$searchQuery%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">' . $row['name'] . '</div>';
        }
    } else {
        echo "No products found.";
    }
}
