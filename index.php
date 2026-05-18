<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<?php if (isset($_COOKIE['past_name']) && $_COOKIE['past_name'] != ''): ?>
    <div style="background: #fff8f0; border: 1px solid #d4a96a; padding: 15px; margin-bottom: 20px;">
        <strong>Welcome back!</strong> Your last purchase was:
        <strong><?php echo $_COOKIE['past_name']; ?></strong>
        — <a href="past_purchases.php">View full history</a>
    </div>
<?php endif; ?>

<div class="container">

    <h2>Welcome to MyAbaya</h2>
    <p style="margin-bottom: 20px; color: #555;">
        Discover our exclusive collection of handcrafted abayas.
    </p>

    <h2>Our Products</h2>

    <?php
    $sql = "SELECT * FROM Products ORDER BY id ASC";
    $result = $pdo->query($sql);

    while ($row = $result->fetch()):
        ?>

        <div
            style="width: 200px; background: #fff; border: 1px solid #ddd; padding: 15px; text-align: center; display: inline-block; vertical-align: top; margin: 0 10px 20px 0;">

            <a href="product.php?id=<?php echo $row['id']; ?>">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="160" height="160">
            </a>

            <h4 style="margin: 8px 0 5px;"><?php echo $row['name']; ?></h4>
            <p style="color: #d4a96a; font-weight: bold;">SAR <?php echo number_format($row['price'], 2); ?></p>

            <?php if ($row['stock'] > 0): ?>
                <p style="color: green; font-size: 13px;">In Stock: <?php echo $row['stock']; ?></p>
            <?php else: ?>
                <p style="color: red; font-size: 13px;">Out of Stock</p>
            <?php endif; ?>

            <a href="product.php?id=<?php echo $row['id']; ?>" class="btn" style="margin-top: 8px;">
                View Details
            </a>
        </div>

    <?php endwhile; ?>

</div>

<?php include 'includes/footer.php'; ?>