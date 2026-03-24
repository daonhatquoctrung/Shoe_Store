<?php
class OrderModel {
    private $db;
    public function __construct() { $this->db = new Database(); }

    // DÀNH CHO NHÂN VIÊN: Lấy danh sách đơn hàng theo Tab và tìm kiếm mã đơn
    public function getStaffOrders($tab = 'Đơn hàng mới', $search = '', $limit = 10, $offset = 0) {
        $conn = $this->db->getConnection();
        
        $statusMap = [
            'Đơn hàng mới' => 'Hiện tại',
            'Đang xử lí'   => 'Đã xử lý',
            'Hoàn tất'     => 'Hoàn tất'
        ];
        $dbStatus = $statusMap[$tab] ?? 'Hiện tại';

        $sql = "SELECT * FROM orders WHERE status = :status";
        if (!empty($search)) {
            $sql .= " AND id = :search";
        }
        
        $sql .= " ORDER BY order_date DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':status', $dbStatus);
        if (!empty($search)) {
            $stmt->bindValue(':search', $search, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xem chi tiết đơn hàng theo ID
    public function getOrderById($id) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Cập nhật trạng thái của đơn hàng (Xác nhận, Hủy,...)
    public function updateStatus($id, $status) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Đếm số lượng đơn hàng để phục vụ chia trang
    public function countStaffOrders($tab, $search = '') {
        $conn = $this->db->getConnection();
        $statusMap = [
            'Đơn hàng mới' => 'Hiện tại',
            'Đang xử lí'   => 'Đã xử lý',
            'Hoàn tất'     => 'Hoàn tất'
        ];
        $dbStatus = $statusMap[$tab] ?? 'Hiện tại';

        $sql = "SELECT COUNT(*) FROM orders WHERE status = :status";
        if (!empty($search)) {
            $sql .= " AND id = :search";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':status', $dbStatus);
        if (!empty($search)) $stmt->bindValue(':search', $search);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // DÀNH CHO ADMIN: Lấy danh sách đơn hàng có phân trang và lọc theo từ khóa
    public function getOrdersPaged($status = 'Hiện tại', $keyword = '', $limit = 10, $offset = 0) {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM orders WHERE status LIKE :status";
        if (!empty($keyword)) {
            $sql .= " AND (id LIKE :key OR product_name LIKE :key OR customer_name LIKE :key)";
        }
        $sql .= " ORDER BY order_date DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':status', $status);
        if (!empty($keyword)) $stmt->bindValue(':key', "%$keyword%");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // BỔ SUNG: Xuất toàn bộ danh sách đơn hàng ra file CSV (Sửa lỗi undefined method)
    public function getAllOrdersForExport($status = 'Hiện tại') {
        $conn = $this->db->getConnection();
        
        // Truy vấn lấy dữ liệu thô phục vụ xuất file Excel
        $sql = "SELECT id, product_name, quantity, customer_name, order_date 
                FROM orders 
                WHERE status = :status 
                ORDER BY order_date DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}