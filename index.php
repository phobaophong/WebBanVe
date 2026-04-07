<?php
// 1. Gọi file kết nối CSDL (Đảm bảo đường dẫn đúng)
require_once 'config/database.php';

// 2. Gọi header (bao gồm luôn navbar)
include 'includes/header.php';

// 3. Viết câu truy vấn lấy các trận đấu "Sắp diễn ra"
// Dùng JOIN để lấy tên đội bóng và tên giải đấu thay vì chỉ lấy ID
$sql = "SELECT 
            td.id AS id_trandau, 
            td.thoi_gian, 
            td.san_van_dong, 
            dn.ten_doi AS ten_doi_nha, 
            dk.ten_doi AS ten_doi_khach, 
            gd.ten_giai
        FROM tbl_trandau td
        JOIN tbl_doibong dn ON td.id_doi_nha = dn.id
        JOIN tbl_doibong dk ON td.id_doi_khach = dk.id
        JOIN tbl_giaidau gd ON td.id_giaidau = gd.id
        WHERE td.trang_thai = 'sap_dien_ra'
        ORDER BY td.thoi_gian ASC
        LIMIT 6"; // Lấy 6 trận gần nhất đưa ra trang chủ

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $danh_sach_tran = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Lỗi truy xuất dữ liệu: " . $e->getMessage();
}
?>

<div class="container">
    <h2 class="section-title">Các Trận Đấu Sắp Tới</h2>

    <?php if (isset($error_message)): ?>
        <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
    <?php elseif (empty($danh_sach_tran)): ?>
        <p style="text-align: center; color: #777;">Hiện tại chưa có trận đấu nào sắp diễn ra.</p>
    <?php else: ?>
        <div class="match-grid">
            <?php foreach ($danh_sach_tran as $tran): ?>
                <div class="match-card">
                    <div class="match-league">
                        🏆 <?php echo htmlspecialchars($tran['ten_giai']); ?>
                    </div>
                    
                    <div class="match-teams">
                        <div class="team">
                            <div class="team-logo">
                                <span>🏠</span> 
                            </div>
                            <div class="team-name"><?php echo htmlspecialchars($tran['ten_doi_nha']); ?></div>
                        </div>
                        
                        <div class="vs-badge">VS</div>
                        
                        <div class="team">
                            <div class="team-logo">
                                <span>✈️</span>
                            </div>
                            <div class="team-name"><?php echo htmlspecialchars($tran['ten_doi_khach']); ?></div>
                        </div>
                    </div>
                    
                    <div class="match-info">
                        <p>📅 <strong>Thời gian:</strong> <?php echo date('H:i - d/m/Y', strtotime($tran['thoi_gian'])); ?></p>
                        <p>🏟️ <strong>Sân vận động:</strong> <?php echo htmlspecialchars($tran['san_van_dong']); ?></p>
                    </div>
                    
                    <a href="pages/checkout.php?id=<?php echo $tran['id_trandau']; ?>" class="btn-buy">Mua Vé Ngay</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
// 4. Gọi footer để đóng trang
include 'includes/footer.php';
?>