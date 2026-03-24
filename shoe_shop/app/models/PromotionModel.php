<?php
class PromotionModel {
    private $db;
    public function __construct() { $this->db = new Database(); }

    public function getAllPromotions() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM promotions ORDER BY start_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionStats() {
        $conn = $this->db->getConnection();
        $today = date('Y-m-d');
        
        $sqlActive = "SELECT COUNT(*) as count FROM promotions WHERE end_date >= ?";
        $stmtA = $conn->prepare($sqlActive);
        $stmtA->execute([$today]);
        $active = $stmtA->fetch(PDO::FETCH_ASSOC)['count'];

        $sqlExpired = "SELECT COUNT(*) as count FROM promotions WHERE end_date < ?";
        $stmtE = $conn->prepare($sqlExpired);
        $stmtE->execute([$today]);
        $expired = $stmtE->fetch(PDO::FETCH_ASSOC)['count'];

        return ['active' => $active, 'expired' => $expired];
    }
    // Thêm mã giảm giá mới
public function addPromotion($data) {
    try {
        $conn = $this->db->getConnection();
        $sql = "INSERT INTO promotions (code, apply_to, discount_percent, start_date, end_date) 
                VALUES (:code, :apply_to, :discount_percent, :start_date, :end_date)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    } catch (PDOException $e) { return false; }
}

// Cập nhật thông tin khuyến mãi
public function updatePromotion($id, $data) {
    try {
        $conn = $this->db->getConnection();
        $sql = "UPDATE promotions SET code = :code, apply_to = :apply_to, 
                discount_percent = :discount_percent, start_date = :start_date, 
                end_date = :end_date WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    } catch (PDOException $e) { return false; }
}

// Xóa mã khuyến mãi
public function deletePromotion($id) {
    try {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM promotions WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) { return false; }
}

// Lấy thông tin 1 mã khuyến mãi theo ID
public function getPromotionById($id) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT * FROM promotions WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}