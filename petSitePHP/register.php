<?php
include_once "config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        
        header("Location: index.php");
        exit();
    } else {
        echo "Ошибка при регистрации: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Реєстрація</h2>
        <form action="register.php" method="post">
            <label for="username">Ім'я користувача:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Пароль:</label>
            <input type="password" name="password" required>

            <button type="submit">Зареєструватися</button>
        </form>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
