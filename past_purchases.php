<?php
session_start();
include 'includes/header.php';
?>

<div class="container">

    <h2>Your Past Purchases</h2>

    <?php
    if (!isset($_COOKIE['past_name']) || $_COOKIE['past_name'] == '') {

        echo '<p style="color: #555;">You have no past purchases yet. Start shopping to see your purchase history here.</p>';
        echo '<p style="margin-top: 15px;"><a href="index.php" class="btn">Shop Now</a></p>';

    } else {

        $past_name  = $_COOKIE['past_name'];
        $past_price = $_COOKIE['past_price'];
        $past_image = $_COOKIE['past_image'];
        $past_qty   = $_COOKIE['past_qty'];

        echo '<p style="margin-bottom: 20px; color: #555;">Here is your last purchased item:</p>';

        echo '<div style="width: 200px; background: #fff; border: 1px solid #ddd; padding: 15px; text-align: center; display: inline-block; vertical-align: top;">';

        if ($past_image != '') {
            echo '<img src="images/' . $past_image . '" alt="' . $past_name . '" width="160" height="160">';
        }

        echo '<h4 style="margin: 8px 0 5px;">' . $past_name . '</h4>';
        echo '<p style="color: #d4a96a; font-weight: bold;">SAR ' . number_format((float)$past_price, 2) . '</p>';
        echo '<p style="color: #555; font-size: 13px;">Qty purchased: ' . (int)$past_qty . '</p>';

        echo '</div>';

        echo '<div style="margin-top: 25px;">';
        echo '<a href="clear_purchases.php" id="clearHistoryBtn" class="btn btn-danger">Clear History</a>';
        echo '</div>';
    }
    ?>

    <p style="margin-top: 20px;"><a href="index.php">&larr; Back to Products</a></p>

</div>

<script>
var clearBtn = document.getElementById('clearHistoryBtn');
if (clearBtn) {
    clearBtn.addEventListener('click', function(event) {
        if (!confirm('Clear your purchase history?')) {
            event.preventDefault();
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
