<?php
session_start();
include 'includes/db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT * FROM Products WHERE id = ?";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $id);
$statement->execute();
$product = $statement->fetch();

if (!$product) {
    include 'includes/header.php';
    echo '<div class="container"><p class="msg-error">Product not found.</p></div>';
    include 'includes/footer.php';
    exit;
}

$msg = '';

if (isset($_POST['add_to_cart'])) {

    $qty = (int) $_POST['quantity'];
    $color = isset($_POST['color']) ? $_POST['color'] : '';
    $size = isset($_POST['size']) ? $_POST['size'] : '';

    if ($qty <= 0) {
        $msg = '<p class="msg-error">Please enter a valid quantity.</p>';

    } elseif ($qty > $product['stock']) {
        $msg = '<p class="msg-error">Sorry, only ' . $product['stock'] . ' item(s) available in stock.</p>';

    } elseif ($color == '' || $size == '') {
        $msg = '<p class="msg-error">Please select a color and size.</p>';

    } else {

        $cart_key = $product['id'];

        if (isset($_SESSION['cart'][$cart_key])) {
            $new_qty = $_SESSION['cart'][$cart_key]['qty'] + $qty;

            if ($new_qty > $product['stock']) {
                $msg = '<p class="msg-error">Cannot add more. Only ' . $product['stock'] . ' available.</p>';
            } else {
                $_SESSION['cart'][$cart_key]['qty'] = $new_qty;
                $msg = '<p class="msg-success">Cart updated successfully!</p>';
            }

        } else {
            $_SESSION['cart'][$cart_key] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => $qty,
                'image' => $product['image'],
                'color' => $color,
                'size' => $size
            );

            $msg = '<p class="msg-success">Item added to cart!</p>';
        }
    }
}

include 'includes/header.php';

$colors = explode(',', $product['color']);
$sizes = explode(',', $product['size']);
?>

<div class="container">

    <?php echo $msg; ?>

    <h2><?php echo $product['name']; ?></h2>

    <table border="0" cellpadding="10">
        <tr>
            <td valign="top">
                <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="300"
                    height="320" style="border: 1px solid #ddd;">
            </td>

            <td valign="top" style="max-width: 420px;">

                <p style="font-size: 22px; color: #d4a96a; font-weight: bold; margin-bottom: 10px;">
                    SAR <?php echo number_format($product['price'], 2); ?>
                </p>

                <p style="margin-bottom: 15px; color: #555; line-height: 1.6;">
                    <?php echo $product['description']; ?>
                </p>

                <p style="margin-bottom: 20px;">
                    <?php if ($product['stock'] > 0): ?>
                        <span style="color: green;">In Stock (<?php echo $product['stock']; ?> available)</span>
                    <?php else: ?>
                        <span style="color: red;">Out of Stock</span>
                    <?php endif; ?>
                </p>

                <?php if ($product['stock'] > 0): ?>

                    <form id="addCartForm" method="POST">

                        <label for="color"><strong>Color:</strong></label><br>
                        <select id="color" name="color"
                            style="width: 160px; padding: 6px; margin: 8px 0; font-size: 15px; border: 1px solid #ccc;">
                            <option value="">-- Select Color --</option>

                            <?php foreach ($colors as $c): ?>
                                <option value="<?php echo $c; ?>">
                                    <?php echo $c; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                        <br>

                        <label for="size"><strong>Size:</strong></label><br>
                        <select id="size" name="size"
                            style="width: 160px; padding: 6px; margin: 8px 0; font-size: 15px; border: 1px solid #ccc;">
                            <option value="">-- Select Size --</option>

                            <?php foreach ($sizes as $s): ?>
                                <option value="<?php echo $s; ?>">
                                    <?php echo $s; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                        <br>

                        <label for="quantity"><strong>Quantity:</strong></label><br>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            max="<?php echo $product['stock']; ?>"
                            style="width: 70px; padding: 6px; margin: 8px 0; font-size: 15px; border: 1px solid #ccc;">
                        <br>

                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                        <a href="cart.php" class="btn" style="margin-left: 10px;">&#x1F6D2; View Cart</a>

                    </form>

                <?php else: ?>

                    <p class="msg-error">This product is currently out of stock.</p>

                <?php endif; ?>

            </td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <button type="button" id="showHelpBtn" class="btn">? Help</button>

        <div id="helpBox"
            style="display: none; margin-top: 15px; background: #fff8f0; border: 1px solid #d4a96a; padding: 15px;">
            <h3 style="color: #1a0a00; margin-bottom: 10px;">How to Order</h3>

            <ul style="padding-left: 20px; line-height: 1.8; color: #444;">
                <li>Select your preferred color and size</li>
                <li>Choose your desired quantity</li>
                <li>Click <strong>Add to Cart</strong></li>
                <li>Click the cart icon or <strong>View Cart</strong> to review your items</li>
                <li>Proceed to <strong>Checkout</strong> to complete your purchase</li>
            </ul>

            <button type="button" id="closeHelpBtn" class="btn" style="margin-top: 10px;">Close</button>
        </div>
    </div>

    <p style="margin-top: 15px;"><a href="index.php">&larr; Back to Products</a></p>

</div>

<script>
    function showHelp() {
        document.getElementById('helpBox').style.display = 'block';
    }

    function closeHelp() {
        document.getElementById('helpBox').style.display = 'none';
    }

    function validateAddCart() {
        var color = document.getElementById('color').value;
        var size = document.getElementById('size').value;
        var qty = parseInt(document.getElementById('quantity').value);

        if (color == '') {
            alert('Please select a color.');
            return false;
        }

        if (size == '') {
            alert('Please select a size.');
            return false;
        }

        if (isNaN(qty) || qty <= 0) {
            alert('Please enter a valid quantity (must be greater than 0).');
            return false;
        }

        if (qty > <?php echo $product['stock']; ?>) {
            alert('Quantity cannot exceed available stock (<?php echo $product['stock']; ?>).');
            return false;
        }

        return true;
    }

    document.getElementById('showHelpBtn').addEventListener('click', showHelp);
    document.getElementById('closeHelpBtn').addEventListener('click', closeHelp);

    var addCartForm = document.getElementById('addCartForm');

    if (addCartForm) {
        addCartForm.addEventListener('submit', function (event) {
            if (!validateAddCart()) {
                event.preventDefault();
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>