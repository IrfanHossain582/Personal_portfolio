<?php
// admin/logout.php
setcookie('admin_token', '', time() - 3600, '/');
header('Location: /login');
exit;
?>
