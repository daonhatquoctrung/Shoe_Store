<?php
class ReviewModel {
    private $db;
    public function __construct() { $this->db = new Database(); }

    // Tab 1: Lấy danh sách chi tiết các đánh giá
    public function getAllReviews() {
        $conn = $this->db->getConnection();
        // Cập nhật u.full_name theo đúng cấu trúc bảng users của bạn
        $sql = "SELECT r.*, p.product_name, u.full_name as customer_name 
                FROM reviews r
                JOIN products p ON r.product_id = p.id
                JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tab 2: Thống kê đánh giá theo từng sản phẩm
    public function getReviewStats() {
        $conn = $this->db->getConnection();
        // Tính toán lượt mua và sao trung bình cho mỗi sản phẩm
        $sql = "SELECT p.product_name, 
                (SELECT COUNT(*) FROM order_items WHERE product_id = p.id) as total_sales,
                (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
                FROM products p";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteReview($id) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) { return false; }
    }
}