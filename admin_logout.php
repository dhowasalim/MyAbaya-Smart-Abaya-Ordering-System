<?php
session_start();

unset($_SESSION['admin_id']);
unset($_SESSION['admin_user']);
session_destroy();

setcookie('admin_username', '', time() - 3600);

header('Location: admin_login.php');
exit;
?>
