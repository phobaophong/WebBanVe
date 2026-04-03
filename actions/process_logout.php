<?php
// File: actions/process_logout.php
session_start();
session_unset();    // Xóa tất cả các biến session (Tên, số dư, id...)
session_destroy();  // Hủy hoàn toàn phiên làm việc
header("Location: ../index.php"); // Đá về trang chủ
exit();
?>