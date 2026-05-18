<?php

session_start();
include 'includes/db_connect.php';

$msg = '';

if (isset($_GET['delete'])) {
    $del_key = (int) $_GET['delete'];
    if (isset($_SESSION['cart'][$del_key])) {
        unset($_SESSION['cart'][$del_key]);
        $msg = '<p class="msg-success">Item removed.</p>';
    }
}

if (isset($_GET['empty'])) {
    $_SESSION['cart'] = array();
    $msg = '<p class="msg-success">Cart has been emptied.</p>';
}

if (isset($_POST['update_cart'])) {

    $error_found = false;

    foreach ($_POST['qty'] as $cart_key => $new_qty) {

        $cart_key = (int) $cart_key;
        $new_qty = (int) $new_qty;

        if ($new_qty <= 0) {
            unset($_SESSION['cart'][$cart_key]);
        } else {
            $sql = "SELECT stock FROM Products WHERE id = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $cart_key);
            $statement->execute();
            $p = $statement->fetch();

            if (!$p) {
                unset($_SESSION['cart'][$cart_key]);
            } elseif ($new_qty > $p['stock']) {
                $error_found = true;
            } else {
                $_SESSION['cart'][$cart_key]['qty'] = $new_qty;
            }
        }
    }

    if ($error_found) {
        $msg = '<p class="msg-error">Not enough stock for one or more items.</p>';
    } else {
        $msg = '<p class="msg-success">Cart updated!</p>';
    }
}

if (isset($_POST['buy'])) {

    if (empty($_SESSION['cart'])) {

        $msg = '<p class="msg-error">Your cart is empty.</p>';

    } else {

        $all_ok = true;

        foreach ($_SESSION['cart'] as $cart_key => $item) {
            $pid = (int) $cart_key;
            $sql = "SELECT stock FROM Products WHERE id = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $pid);
            $statement->execute();
            $p = $statement->fetch();

            if (!$p || $p['stock'] < $item['qty']) {
                $all_ok = false;
            }
        }

        if (!$all_ok) {

            $msg = '<p class="msg-error">Could not complete purchase. Check stock quantities.</p>';

        } else {

            $last_item = array();

            foreach ($_SESSION['cart'] as $cart_key => $item) {
                $pid = (int) $cart_key;
                $qty = (int) $item['qty'];

                $sql = "UPDATE Products SET stock = stock - ? WHERE id = ?";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(1, $qty);
                $statement->bindValue(2, $pid);
                $statement->execute();

                $last_item = $item;
            }

            if (!empty($last_item)) {
                setcookie('past_name', $last_item['name'], time() + 60 * 60 * 24 * 30);
                setcookie('past_price', $last_item['price'], time() + 60 * 60 * 24 * 30);
                setcookie('past_image', $last_item['image'], time() + 60 * 60 * 24 * 30);
                setcookie('past_qty', $last_item['qty'], time() + 60 * 60 * 24 * 30);
            }

            $_SESSION['cart'] = array();

            $msg = 'ORDER_PLACED';
        }
    }
}


include 'includes/header.php';
?>

<div class="container">

    <h2>Checkout</h2>

    <?php
    if ($msg == 'ORDER_PLACED'):
        ?>
        <div class="msg-success" style="font-size: 16px; padding: 20px;">
            <strong>Order placed successfully!</strong><br>
            Thank you for shopping at MyAbaya.<br><br>
            <a href="index.php" class="btn">Continue Shopping</a>
            <a href="past_purchases.php" class="btn" style="margin-left: 10px;">View Past Purchases</a>
        </div>

    <?php elseif (!empty($_SESSION['cart'])): ?>

        <?php echo $msg; ?>

        <form id="checkoutForm" method="POST">

            <table border="1" cellpadding="10" cellspacing="0" width="100%"
                style="border-collapse: collapse; background: #fff;">

                <tr style="background: #1a0a00; color: #f5e6d3;">
                    <th>Image</th>
                    <th>Product</th>
                    <th>Color / Size</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Item Total</th>
                    <th>Action</th>
                </tr>

                <?php
                $grand_total = 0;
                foreach ($_SESSION['cart'] as $cart_key => $item):
                    $item_total = $item['price'] * $item['qty'];
                    $grand_total = $grand_total + $item_total;
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <img src="images/<?php echo $item['image']; ?>" width="60" height="60">
                        </td>
                        <td><?php echo $item['name']; ?></td>
                        <td>
                            <?php echo $item['color']; ?> /
                            <?php echo $item['size']; ?>
                        </td>
                        <td>SAR <?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <input type="number" name="qty[<?php echo $cart_key; ?>]" value="<?php echo $item['qty']; ?>"
                                min="0" style="width: 60px; padding: 4px; border: 1px solid #ccc;">
                        </td>
                        <td>SAR <?php echo number_format($item_total, 2); ?></td>
                        <td>
                            <a href="checkout.php?delete=<?php echo $cart_key; ?>" class="btn btn-danger confirm-link"
                                data-message="Remove this item?">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr style="font-weight: bold; background: #fdf8f4;">
                    <td colspan="5" style="text-align: right; padding-right: 15px;">Grand Total:</td>
                    <td>SAR <?php echo number_format($grand_total, 2); ?></td>
                    <td></td>
                </tr>

            </table>

            <div style="margin-top: 15px;">
                <button type="submit" name="update_cart" class="btn">Update Cart</button>
                <a href="checkout.php?empty=1" class="btn btn-danger confirm-link" data-message="Delete all items?">Delete
                    All</a>
                <button type="submit" name="buy" id="buyNowBtn" class="btn" style="background: #2d6a4f;">Buy Now</button>
                <a href="index.php" class="btn">Continue Shopping</a>
            </div>

        </form>

    <?php else: ?>

        <?php echo $msg; ?>
        <p>Your cart is empty. <a href="index.php">Go Shopping</a></p>

    <?php endif; ?>

</div>

<script>
    function validateCheckout() {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'number') {
                var val = parseInt(inputs[i].value);
                if (isNaN(val) || val < 0) {
                    alert('Please enter a valid quantity (0 or more).');
                    return false;
                }
            }
        }
        return true;
    }

    var checkoutForm = document.getElementById('checkoutForm');

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (event) {
            if (!validateCheckout()) {
                event.preventDefault();
            }
        });
    }

    var buyBtn = document.getElementById('buyNowBtn');
    if (buyBtn) {
        buyBtn.addEventListener('click', function (event) {
            if (!confirm('Confirm your order?')) {
                event.preventDefault();
            }
        });
    }

    var links = document.getElementsByClassName('confirm-link');
    for (var i = 0; i < links.length; i++) {
        links[i].addEventListener('click', function (event) {
            var message = this.getAttribute('data-message');
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>