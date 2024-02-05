<?php
include_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            header("Location: index.php");
            exit();
        } else {
            echo "Неправильний пароль";
        }
    } else {
        echo "Користувач із зазначеним email не знайдений";
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
    <title>Вхід</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>

    <div class="container">
        <h2>Вхід</h2>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Пароль:</label>
            <input type="password" name="password" required>

            <button type="submit">Увійти</button>
        </form>
    </div>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>
