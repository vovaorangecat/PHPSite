<?php
session_start(); 
$userLoggedIn = isset($_SESSION['username']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин іграшок</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="" type="image/x-icon">
</head>
<body>

<header>
    <div class="container">
        <a href="index.php"><img src="Фон.png" alt="Логотип магазина" class="logo"></a>
        <h1>Магазин іграшок</h1>
        <nav>
            <ul>
                <li><a href="index.php">Головна</a></li>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="profile.php">ссесія</a></li>
                <li><a href="checkout.php">замовити</a></li>
                <li><a href="userOrder.php">перевірити список замовлень</a></li>
                <?php
                if ($userLoggedIn) {
                    echo '<li><a href="logout.php">Вийти</a></li>';
                } else {
                    echo '<li><a href="register.php">Реєстрація</a></li>';
                    echo '<li><a href="login.php">Вхід</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>
