<?php
class PaymentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Lấy toàn bộ lịch sử giao dịch
    public function getAllPayments() {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM payments ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm giao dịch theo mã thanh toán hoặc thông tin đơn hàng (Tên khách, Tên SP)
    public function searchPayments($keyword) {
        $conn = $this->db->getConnection();
        $key = "%$keyword%";
        $sql = "SELECT * FROM payments 
                WHERE payment_code LIKE :key 
                OR order_name LIKE :key 
                OR customer_name LIKE :key 
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra điều kiện hoàn tiền (Ví dụ: Trạng thái phải là 'Completed' và chưa hoàn tiền)
    public function checkRefundEligibility($paymentId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT status FROM payments WHERE id = ?");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        // Giả sử chỉ hoàn tiền khi trạng thái là 'Thành công'
        if ($payment && $payment['status'] === 'Thành công') {
            return true;
        }
        return false;
    }

    // Thực hiện cập nhật trạng thái hoàn tiền trong DB
    public function processRefund($paymentId) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("UPDATE payments SET status = 'Đã hoàn tiền' WHERE id = ?");
            return $stmt->execute([$paymentId]);
        } catch (PDOException $e) {
            return false;
        }
    }
}