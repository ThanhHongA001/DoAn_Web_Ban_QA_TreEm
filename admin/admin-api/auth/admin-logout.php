<?php
require_once '../dp.php';

session_destroy();
header('Location: ../../admin-login.html');
exit;
?>