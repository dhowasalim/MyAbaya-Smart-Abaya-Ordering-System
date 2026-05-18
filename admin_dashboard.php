<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include 'includes/db_connect.php';

$msg = '';

if (isset($_GET['delete'])) {
    $del_id    = (int) $_GET['delete'];
    $sql       = "DELETE FROM Products WHERE id = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $del_id);
    $statement->execute();
    $msg = '<p class="msg-success">Product deleted successfully.</p>';
}

$search = '';

if (isset($_POST['search'])) {
    $search    = $_POST['search_term'];
    $sql       = "SELECT * FROM Products WHERE name LIKE ? ORDER BY id ASC";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, '%' . $search . '%');
    $statement->execute();
    $result = $statement;
} else {
    $sql    = "SELECT * FROM Products ORDER BY id ASC";
    $result = $pdo->query($sql);
}

include 'includes/header.php';
?>

<div class="container">

    <h2>Admin Dashboard</h2>

    <p>
        Welcome, <strong><?php echo $_SESSION['admin_user']; ?></strong>
        &nbsp;|&nbsp;
        <a href="admin_logout.php">Logout</a>
    </p>

    <?php echo $msg; ?>

    <form id="searchForm" method="POST" style="margin: 15px 0;">
        <input type="text" id="search_term" name="search_term"
               value="<?php echo $search; ?>"
               placeholder="Search product by name..."
               style="padding: 8px; width: 280px; border: 1px solid #ccc; font-size: 14px;">
        <button type="submit" name="search" class="btn">Search</button>
        <a href="admin_dashboard.php" class="btn">Show All</a>
        <a href="add_product.php" class="btn">+ Add New Product</a>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" width="100%"
           style="border-collapse: collapse; background: #fff;">

        <tr style="background: #1a0a00; color: #f5e6d3;">
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price (SAR)</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>

        <?php
        $found = false;
        while ($row = $result->fetch()):
            $found = true;
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td>
                <img src="images/<?php echo $row['image']; ?>"
                     width="50" height="50">
            </td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                &nbsp;
                <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>"
                   class="btn btn-danger confirm-link"
                   data-message="Delete this product?">Delete</a>
            </td>
        </tr>
        <?php endwhile;

        if (!$found) {
            echo '<tr><td colspan="6" style="text-align: center;">No products found.</td></tr>';
        }
        ?>

    </table>

</div>

<script>
function validateSearch() {
    var term = document.getElementById('search_term').value;
    if (term == '') {
        alert('Please enter a search term.');
        return false;
    }
    return true;
}

document.getElementById('searchForm').addEventListener('submit', function(event) {
    if (!validateSearch()) {
        event.preventDefault();
    }
});

var links = document.getElementsByClassName('confirm-link');
for (var i = 0; i < links.length; i++) {
    links[i].addEventListener('click', function(event) {
        var message = this.getAttribute('data-message');
        if (!confirm(message)) {
            event.preventDefault();
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
