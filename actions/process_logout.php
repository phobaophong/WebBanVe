<?php
session_start();
session_unset();
session_destroy();

// Chuyển về trang chủ
header("Location: ../index.php");
exit();
?>