<?php
session_start(); 
include_once "config.php";

$orderDetails = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderDetails['email'] = $_POST['email'];
    $orderDetails['first_name'] = $_POST['first_name'];
    $orderDetails['last_name'] = $_POST['last_name'];
    $orderDetails['phone_number'] = $_POST['phone_number'];
    $orderDetails['delivery_method'] = $_POST['delivery_method'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Підтвердження замовлення</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Дякуємо за ваше замовлення!</h2>
        <p>Ми зв'яжемося з вами для уточнення деталей і підтвердження замовлення. Дякуємо за покупку!</p>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
