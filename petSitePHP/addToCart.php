<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

        $itemIndex = -1;
        foreach ($cart as $index => $cartItem) {
            if ($cartItem['product_id'] == $product_id) {
                $itemIndex = $index;
                break;
            }
        }

        if ($itemIndex >= 0) {
            $cart[$itemIndex]['quantity'] += 1;
        } else {
            $cart[] = array(
                'product_id' => $product_id,
                'quantity' => 1
            );
        }

        $_SESSION['cart'] = $cart;
    }
}
header("Location: catalog.php");
exit();
