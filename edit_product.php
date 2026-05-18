<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include 'includes/db_connect.php';

$msg = '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT * FROM Products WHERE id = ?";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $id);
$statement->execute();
$product = $statement->fetch();

if (!$product) {
    header('Location: admin_dashboard.php');
    exit;
}

if (isset($_POST['update_product'])) {

    $name = $_POST['name'];
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $desc = $_POST['description'];
    $color = $_POST['color'];
    $size = $_POST['size'];

    $image_name = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $new_image = time() . '_' . basename($_FILES['image']['name']);
        $upload_path = __DIR__ . '/images/' . $new_image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_name = $new_image;
        } else {
            $msg = '<p class="msg-error">Image upload failed.</p>';
        }
    }

    if ($msg == '') {

        $sql = "UPDATE Products SET name=?, price=?, stock=?, image=?, description=?, color=?, size=? WHERE id=?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $name);
        $statement->bindValue(2, $price);
        $statement->bindValue(3, $stock);
        $statement->bindValue(4, $image_name);
        $statement->bindValue(5, $desc);
        $statement->bindValue(6, $color);
        $statement->bindValue(7, $size);
        $statement->bindValue(8, $id);

        if ($statement->execute()) {
            $msg = '<p class="msg-success">Product updated! <a href="admin_dashboard.php">Back to Dashboard</a></p>';

            $sql = "SELECT * FROM Products WHERE id = ?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $id);
            $statement->execute();
            $product = $statement->fetch();

        } else {
            $msg = '<p class="msg-error">Error updating product.</p>';
        }
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 540px;">

    <h2>Edit Product</h2>
    <p><a href="admin_dashboard.php">&larr; Back to Dashboard</a></p>

    <?php echo $msg; ?>

    <form id="editProductForm" method="POST" enctype="multipart/form-data"
        style="background: #fff; padding: 25px; border: 1px solid #ddd; margin-top: 15px;">

        <label><strong>Product Name:</strong></label><br>
        <input type="text" id="p_name" name="name" value="<?php echo $product['name']; ?>"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Price (SAR):</strong></label><br>
        <input type="text" id="p_price" name="price" value="<?php echo $product['price']; ?>"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Stock Quantity:</strong></label><br>
        <input type="text" id="p_stock" name="stock" value="<?php echo $product['stock']; ?>"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Colors (comma separated):</strong></label><br>
        <input type="text" id="p_color" name="color" value="<?php echo $product['color']; ?>"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Sizes (comma separated):</strong></label><br>
        <input type="text" id="p_size" name="size" value="<?php echo $product['size']; ?>"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Product Image:</strong></label><br>
        <p style="font-size: 13px; color: #555; margin: 5px 0;">
            Current image: <strong><?php echo $product['image']; ?></strong>
        </p>
        <img src="images/<?php echo $product['image']; ?>" width="80" height="80"
            style="margin-bottom: 8px; border: 1px solid #ddd;"><br>
        <input type="file" id="p_image" name="image" accept="image/*"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Description:</strong></label><br>
        <textarea id="p_desc" name="description" rows="3"
            style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;"><?php echo $product['description']; ?></textarea>

        <button type="submit" name="update_product" class="btn">Update Product</button>
        <a href="admin_dashboard.php" class="btn" style="margin-left: 10px;">Cancel</a>

    </form>

</div>

<script>
    function validateEditProduct() {
        var name = document.getElementById('p_name').value;
        var price = document.getElementById('p_price').value;
        var stock = document.getElementById('p_stock').value;

        if (name == '') {
            alert('Product name is required.');
            return false;
        }
        if (isNaN(parseFloat(price)) || parseFloat(price) < 0) {
            alert('Please enter a valid price.');
            return false;
        }
        if (isNaN(parseInt(stock)) || parseInt(stock) < 0) {
            alert('Please enter a valid stock quantity.');
            return false;
        }
        return true;
    }

    document.getElementById('editProductForm').addEventListener('submit', function (event) {
        if (!validateEditProduct()) {
            event.preventDefault();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>