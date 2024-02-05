<?php
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    echo "Hi, $username! Сесія працює.";
} else {
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профіль користувача</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include_once "includes/header.php"; ?>


    <?php include_once "includes/footer.php"; ?>

</body>
</html>
