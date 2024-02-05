<?php
include_once "config.php";

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        header("Location: catalog.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: catalog.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детальна інформація про товар</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Детальна інформація про товар</h2>

        <div class="product-details">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <p>Ціна: $<?php echo $product['price']; ?></p>
            <a href="buy.php?id=<?php echo $product['id']; ?>">Купити</a>
        </div>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
