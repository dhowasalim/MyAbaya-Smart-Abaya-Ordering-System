<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

include 'includes/db_connect.php';

$msg = '';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql       = "SELECT * FROM Admin WHERE username = ? AND password = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $username);
    $statement->bindValue(2, $password);
    $statement->execute();
    $admin = $statement->fetch();

    if ($admin) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        setcookie('admin_username', $admin['username'], time() + 60 * 60 * 24);
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $msg = '<p class="msg-error">Invalid username or password. Please try again.</p>';
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 420px;">

    <h2>Admin Login</h2>

    <?php echo $msg; ?>

    <form id="loginForm" method="POST"
          style="background: #fff; padding: 25px; border: 1px solid #ddd; margin-top: 15px;">

        <label for="username"><strong>Username:</strong></label><br>
        <input type="text" id="username" name="username"
               value="<?php echo isset($_COOKIE['admin_username']) ? $_COOKIE['admin_username'] : ''; ?>"
               style="width: 100%; padding: 8px; margin: 6px 0 15px; border: 1px solid #ccc; font-size: 15px;">

        <label for="password"><strong>Password:</strong></label><br>
        <input type="password" id="password" name="password"
               style="width: 100%; padding: 8px; margin: 6px 0 20px; border: 1px solid #ccc; font-size: 15px;">

        <button type="submit" name="login" class="btn" style="width: 100%;">Login</button>
    </form>

</div>

<script>
function validateLogin() {
    var user = document.getElementById('username').value;
    var pass = document.getElementById('password').value;

    if (user == '') {
        alert('Please enter your username.');
        return false;
    }
    if (pass == '') {
        alert('Please enter your password.');
        return false;
    }
    return true;
}

document.getElementById('loginForm').addEventListener('submit', function(event) {
    if (!validateLogin()) {
        event.preventDefault();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
