<?php
setcookie('past_name',  '', time() - 3600);
setcookie('past_price', '', time() - 3600);
setcookie('past_image', '', time() - 3600);
setcookie('past_qty',   '', time() - 3600);

header('Location: past_purchases.php');
exit;
?>
