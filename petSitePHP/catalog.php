<?php
session_start();

include_once "config.php";
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = array();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товарів</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Каталог товарів</h2>

        <div class="product-container">
            <?php
            foreach ($products as $product) {
                echo '<div class="product">';
                echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                echo '<h3>' . $product['name'] . '</h3>';
                echo '<p>' . $product['description'] . '</p>';
                echo '<p> Ціна: ' . $product['price'] .'₴ </p>';
                echo '<form method="post" action="addToCart.php">';
                echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
                echo '<input type="submit" value="Додати в кошик">';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
