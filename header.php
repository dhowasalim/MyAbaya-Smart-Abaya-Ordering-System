<?php

$cart_count = 0;
$cart_total = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count = $cart_count + $item['qty'];
        $cart_total = $cart_total + ($item['price'] * $item['qty']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyAbaya - Smart Abaya Ordering System</title>
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #fdf8f4;
            color: #2c2c2c;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #1a0a00;
            color: #f5e6d3;
            padding: 15px 30px;
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #d4a96a;
            text-decoration: none;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar ul li {
            display: inline;
            margin-left: 20px;
        }

        .navbar ul li a {
            color: #f5e6d3;
            text-decoration: none;
            font-size: 15px;
        }

        .navbar ul li a:hover {
            color: #d4a96a;
        }

        .cart-bar {
            background-color: #3b1f0a;
            color: #f5e6d3;
            text-align: right;
            padding: 8px 30px;
            font-size: 14px;
        }

        .cart-bar a {
            color: #d4a96a;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            color: #1a0a00;
            margin-bottom: 20px;
            border-bottom: 2px solid #d4a96a;
            padding-bottom: 8px;
        }

        .btn {
            background-color: #1a0a00;
            color: #f5e6d3;
            padding: 9px 18px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #3b1f0a;
        }

        .btn-danger {
            background-color: #8b0000;
        }

        .btn-danger:hover {
            background-color: #a00000;
        }

        .msg-success {
            background: #e6f4ea;
            color: #2d6a4f;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #2d6a4f;
        }

        .msg-error {
            background: #fdecea;
            color: #8b0000;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #8b0000;
        }

        .footer {
            background-color: #1a0a00;
            color: #aaa;
            text-align: center;
            padding: 15px;
            margin-top: 50px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <a href="index.php" class="logo">MyAbaya</a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="past_purchases.php">Past Purchases</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="return_policy.php">Return Policy</a></li>
            <li><a href="admin_login.php">Admin</a></li>
            <li>
                <a href="cart.php" title="View Cart" aria-label="View Cart">
                    <img src="./images/Cart.png" alt="Shopping Cart" width="30" height="30"
                        style="vertical-align: middle; margin-top: 0.5px;">
                    <span style="background:#d4a96a; color:#1a0a00; padding:5px 8px; font-size:14px; font-weight:bold;">
                        <?php echo $cart_count; ?>
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <div class="cart-bar">
        Cart: <strong><?php echo $cart_count; ?></strong> item(s)
        &nbsp;|&nbsp;
        Total: <strong>SAR <?php echo number_format($cart_total, 2); ?></strong>
        &nbsp;&nbsp;<a href="cart.php">Edit Cart</a>
        &nbsp;|&nbsp;<a href="checkout.php">Checkout</a>
    </div>