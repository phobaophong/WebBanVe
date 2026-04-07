<?php
$host = 'localhost';
$dbname = 'banve_bongda'; 
$username = 'root';        
$password = 'vertrigo';     

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Kết nối CSDL banve_worldcup qua Vertrigo thành công!"; 

} catch(PDOException $e) {
    die("<h3 style='color:red;'>Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage() . "</h3>");
}
?>