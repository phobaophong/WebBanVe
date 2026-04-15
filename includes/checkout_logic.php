<?php
session_start();

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    $id_trandau = isset($_GET['id_trandau']) ? $_GET['id_trandau'] : '';
    $_SESSION['error'] = "Bạn cần đăng nhập để tiến hành đặt vé!";
    if ($id_trandau != '') {
        header("Location: login.php?redirect=checkout.php?id_trandau=" . $id_trandau);
    } else {
        header("Location: login.php");
    }
    exit();
}

require_once '../config/database.php'; 

// 2. LẤY THÔNG TIN TRẬN ĐẤU VÀ VÉ
$id_trandau = isset($_GET['id_trandau']) ? (int)$_GET['id_trandau'] : 0;

if ($id_trandau <= 0) {
    header("Location: ../index.php");
    exit();
}

// Lấy thông tin chi tiết trận đấu
$sql_match = "SELECT t.*, g.ten_giai, dn.ten_doi AS ten_nha, dk.ten_doi AS ten_khach 
              FROM tbl_trandau t
              JOIN tbl_giaidau g ON t.id_giaidau = g.id
              JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
              JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
              WHERE t.id = :id_trandau LIMIT 1";
$stmt = $conn->prepare($sql_match);
$stmt->execute(['id_trandau' => $id_trandau]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    die("<div class='container mt-5'><h3 class='text-danger text-center'>Không tìm thấy trận đấu!</h3></div>");
}

// Lấy danh sách các hạng vé 
$sql_tickets = "SELECT v.*, h.ten_hang 
                FROM tbl_ve v 
                JOIN tbl_hangve h ON v.id_hangve = h.id 
                WHERE v.id_trandau = :id_trandau AND v.so_luong_con > 0";
$stmt_tickets = $conn->prepare($sql_tickets);
$stmt_tickets->execute(['id_trandau' => $id_trandau]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);
?>