<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include 'includes/db_connect.php';

$msg = '';

if (isset($_POST['add_product'])) {

    $name  = $_POST['name'];
    $price = (float) $_POST['price'];
    $stock = (int)   $_POST['stock'];
    $desc  = $_POST['description'];
    $color = $_POST['color'];
    $size  = $_POST['size'];

    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $image_name  = time() . '_' . basename($_FILES['image']['name']);
        $target_file = __DIR__ . '/images/' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {

            $sql = "INSERT INTO Products (name, price, stock, image, description, color, size) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $name);
            $statement->bindValue(2, $price);
            $statement->bindValue(3, $stock);
            $statement->bindValue(4, $image_name);
            $statement->bindValue(5, $desc);
            $statement->bindValue(6, $color);
            $statement->bindValue(7, $size);

            if ($statement->execute()) {
                $msg = '<p class="msg-success">Product added successfully! <a href="admin_dashboard.php">Back to Dashboard</a></p>';
            } else {
                $msg = '<p class="msg-error">Error adding product to database.</p>';
            }

        } else {
            $msg = '<p class="msg-error">Image upload failed. Please try again.</p>';
        }

    } else {
        $msg = '<p class="msg-error">Please upload a product image.</p>';
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 520px;">

    <h2>Add New Product</h2>
    <p><a href="admin_dashboard.php">&larr; Back to Dashboard</a></p>

    <?php echo $msg; ?>

    <form id="addProductForm" method="POST" enctype="multipart/form-data"
          style="background: #fff; padding: 25px; border: 1px solid #ddd; margin-top: 15px;">

        <label><strong>Product Name:</strong></label><br>
        <input type="text" id="p_name" name="name"
               style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Price (SAR):</strong></label><br>
        <input type="text" id="p_price" name="price"
               style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Stock Quantity:</strong></label><br>
        <input type="text" id="p_stock" name="stock"
               style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Colors (comma separated, e.g. Black,Beige):</strong></label><br>
        <input type="text" id="p_color" name="color" placeholder="Black,Beige"
               style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Sizes (comma separated, e.g. S,M,L,XL):</strong></label><br>
        <input type="text" id="p_size" name="size" placeholder="S,M,L,XL"
               style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Product Image:</strong></label><br>
        <input type="file" id="p_image" name="image" accept="image/*"
               style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;">

        <label><strong>Description:</strong></label><br>
        <textarea name="description" rows="3"
                  style="width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ccc;"></textarea>

        <button type="submit" name="add_product" class="btn">Add Product</button>
        <a href="admin_dashboard.php" class="btn" style="margin-left: 10px;">Cancel</a>

    </form>

</div>

<script>
function validateAdd() {
    var name  = document.getElementById('p_name').value;
    var price = document.getElementById('p_price').value;
    var stock = document.getElementById('p_stock').value;
    var image = document.getElementById('p_image').value;

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
    if (image == '') {
        alert('Please choose a product image to upload.');
        return false;
    }
    return true;
}

document.getElementById('addProductForm').addEventListener('submit', function(event) {
    if (!validateAdd()) {
        event.preventDefault();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
