<?php
session_start(); 
include_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$sqlGetOrders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmtGetOrders = $conn->prepare($sqlGetOrders);
$stmtGetOrders->bind_param("i", $user_id);
$stmtGetOrders->execute();
$resultOrders = $stmtGetOrders->get_result();

$orders = array();

while ($rowOrder = $resultOrders->fetch_assoc()) {
    $order_id = $rowOrder['id'];

    $sqlGetOrderItems = "SELECT order_items.quantity, order_items.price, products.name, products.image
                         FROM order_items
                         JOIN products ON order_items.product_id = products.id
                         WHERE order_items.order_id = ?";
    $stmtGetOrderItems = $conn->prepare($sqlGetOrderItems);
    $stmtGetOrderItems->bind_param("i", $order_id);
    $stmtGetOrderItems->execute();
    $resultOrderItems = $stmtGetOrderItems->get_result();

    $orderItems = array();
    while ($rowOrderItem = $resultOrderItems->fetch_assoc()) {
        $orderItems[] = $rowOrderItem;
    }

    $rowOrder['items'] = $orderItems;
    $orders[] = $rowOrder;

    if ($resultOrderItems !== false) {
        $stmtGetOrderItems->close();
    }
}

$stmtGetOrders->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список замовлень</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Список замовлень</h2>

        <?php if (!empty($orders)) : ?>
            <?php foreach ($orders as $order) : ?>
                <div class="order">
                    <h3>Замовлення №<?php echo $order['id']; ?></h3>
                    <p>Дата замовлення: <?php echo $order['order_date']; ?></p>
                   
                    <p>Загальна сума: ₴<?php echo $order['total_amount']; ?></p>

                    <h4>Товари в замовленні:</h4>
                    <div class="order-items">
                        <?php foreach ($order['items'] as $orderItem) : ?>
                            <div class="order-item">
                                <img src="<?php echo $orderItem['image']; ?>" alt="<?php echo $orderItem['name']; ?>">
                                <p><?php echo $orderItem['name']; ?></p>
                                <p>Кількість: <?php echo $orderItem['quantity']; ?></p>
                                <p>Ціна за одиницю: ₴<?php echo $orderItem['price']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>У вас немає замовлень.</p>
        <?php endif; ?>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
