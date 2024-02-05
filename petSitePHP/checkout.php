<?php
session_start();

include_once "config.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

$cartProducts = array();

foreach ($cartItems as $cartItem) {
    $product_id = $cartItem['product_id'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product['quantity'] = $cartItem['quantity'];
        $cartProducts[] = $product;
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; 
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    $sqlUpdateUserInfo = "INSERT INTO user_info (user_id, first_name, last_name, address, phone_number) VALUES (?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE first_name=VALUES(first_name), last_name=VALUES(last_name), address=VALUES(address), phone_number=VALUES(phone_number)";
    $stmtUpdateUserInfo = $conn->prepare($sqlUpdateUserInfo);
    $stmtUpdateUserInfo->bind_param("issss", $user_id, $first_name, $last_name, $address, $phone_number);
    $stmtUpdateUserInfo->execute();

    $sqlCreateOrder = "INSERT INTO orders (user_id, order_date, status, total_amount) VALUES (?, NOW(), 'сплачено', 0)";
    $stmtCreateOrder = $conn->prepare($sqlCreateOrder);
    $stmtCreateOrder->bind_param("i", $user_id);
    $stmtCreateOrder->execute();
    $order_id = $stmtCreateOrder->insert_id;

    foreach ($cartProducts as $cartProduct) {
        $product_id = $cartProduct['id'];
        $quantity = $cartItem['quantity'];
        $price = $cartProduct['price'];
        
        $totalAmount += $quantity * $price;

        $sqlAddToOrderItems = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmtAddToOrderItems = $conn->prepare($sqlAddToOrderItems);
        $stmtAddToOrderItems->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmtAddToOrderItems->execute();
    }

    $sqlUpdateTotalAmount = "UPDATE orders SET total_amount=? WHERE id=?";
    $stmtUpdateTotalAmount = $conn->prepare($sqlUpdateTotalAmount);
    $stmtUpdateTotalAmount->bind_param("di", $totalAmount, $order_id);
    $stmtUpdateTotalAmount->execute();

    unset($_SESSION['cart']);

    header("Location: order_confirmation.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Оформлення замовлення</h2>

        <?php if (!empty($cartProducts)) : ?>
            <div class="product-container">
                <?php foreach ($cartProducts as $cartProduct) : ?>
                    <div class="product">
                        <img src="<?php echo $cartProduct['image']; ?>" alt="<?php echo $cartProduct['name']; ?>">
                        <h3><?php echo $cartProduct['name']; ?></h3>
                        <p>Кількість: <?php echo $cartProduct['quantity']; ?></p>
                        <p>Ціна: <?php echo $cartProduct['price'] * $cartProduct['quantity']; ?>₴</p>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="post" action="checkout.php">
                <label for="first_name">Ім'я:</label>
                <input type="text" name="first_name" required>

                <label for="last_name">Прізвище:</label>
                <input type="text" name="last_name" required>

                <label for="address">Адреса:</label>
                <input type="text" name="address" required>

                <label for="phone_number">Телефон:</label>
                <input type="text" name="phone_number" required>

                <button type="submit">Оформити замовлення</button>
            </form>
        <?php else : ?>
            <p>Ваш кошик порожній. Будь ласка, додайте товари перед оформленням замовлення.</p>
        <?php endif; ?>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
