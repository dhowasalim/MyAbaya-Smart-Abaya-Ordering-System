<?php
session_start();
include 'includes/db_connect.php';

$msg = '';

if (isset($_GET['delete'])) {
    $del_key = (int) $_GET['delete'];
    if (isset($_SESSION['cart'][$del_key])) {
        unset($_SESSION['cart'][$del_key]);
        $msg = '<p class="msg-success">Item removed from cart.</p>';
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
        $msg = '<p class="msg-error">Not enough stock for one or more items. Quantities not updated.</p>';
    } else {
        $msg = '<p class="msg-success">Cart updated successfully!</p>';
    }
}


include 'includes/header.php';
?>

<div class="container">

    <h2>&#x1F6D2; Shopping Cart</h2>

    <?php echo $msg; ?>

    <?php if (empty($_SESSION['cart'])): ?>

        <p>Your cart is empty. <a href="index.php">Continue Shopping</a></p>

    <?php else: ?>

        <form id="cartForm" method="POST">

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
                            <a href="cart.php?delete=<?php echo $cart_key; ?>" class="btn btn-danger confirm-link"
                                data-message="Remove this item?">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr style="background: #fdf8f4; font-weight: bold;">
                    <td colspan="5" style="text-align: right; padding-right: 15px;">Grand Total:</td>
                    <td>SAR <?php echo number_format($grand_total, 2); ?></td>
                    <td></td>
                </tr>

            </table>

            <div style="margin-top: 15px;">
                <button type="submit" name="update_cart" class="btn">Update Cart</button>
                <a href="cart.php?empty=1" class="btn btn-danger confirm-link" data-message="Empty the entire cart?">Empty
                    Cart</a>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
                <a href="index.php" class="btn">Continue Shopping</a>
            </div>

        </form>

    <?php endif; ?>

</div>

<script>
    function validateCart() {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'number') {
                var val = parseInt(inputs[i].value);
                if (isNaN(val) || val < 0) {
                    alert('Please enter a valid quantity (0 or more) for all items.');
                    return false;
                }
            }
        }
        return true;
    }

    var cartForm = document.getElementById('cartForm');

    if (cartForm) {
        cartForm.addEventListener('submit', function (event) {
            if (!validateCart()) {
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