<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    // --- CHỨC NĂNG AUTH (ĐĂNG NHẬP/ĐĂNG KÝ) ---

    // Đăng ký tài khoản - LƯU MẬT KHẨU TRỰC TIẾP
    public function register($data) {
        $conn = $this->db->getConnection();
        $sql = "INSERT INTO users (username, password, full_name, email, role) VALUES (?, ?, ?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        
        return $stmt->execute([
            $data['username'], 
            $data['password'], 
            $data['full_name'], 
            $data['email']
        ]);
    }

    // Kiểm tra đăng nhập - SO SÁNH TRỰC TIẾP
    public function login($username, $password) {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password == $user['password'])  {
            return $user; 
        }
        return false;
    }

    // --- CHỨC NĂNG QUẢN TRỊ (ADMIN) ---

    // Lấy danh sách nhân viên (role là 'admin' hoặc 'staff')
    public function getEmployees($keyword = '') {
        $conn = $this->db->getConnection();
        $sql = "SELECT id, full_name, phone, role FROM users 
                WHERE (role = 'admin' OR role = 'staff')";
        
        if (!empty($keyword)) {
            $sql .= " AND (full_name LIKE :key OR id LIKE :key)";
        }
        
        $stmt = $conn->prepare($sql);
        if (!empty($keyword)) $stmt->bindValue(':key', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách khách hàng (role là 'customer') kèm số lần mua hàng
    public function getCustomers($keyword = '', $filter = 'name') {
        $conn = $this->db->getConnection();
        
        // SQL tính toán số lần mua hàng từ bảng payments (dựa trên tên khách hàng)
        $sql = "SELECT u.id, u.full_name, 
                (SELECT COUNT(*) FROM payments WHERE customer_name = u.full_name) as purchase_count 
                FROM users u 
                WHERE u.role = 'customer'";

        if (!empty($keyword)) {
            $sql .= " AND (u.full_name LIKE :key OR u.id LIKE :key)";
        }

        // Xử lý bộ lọc theo đặc tả
        switch ($filter) {
            case 'id': $sql .= " ORDER BY u.id ASC"; break;
            case 'purchase': $sql .= " ORDER BY purchase_count DESC"; break;
            default: $sql .= " ORDER BY u.full_name ASC"; break; // Mặc định lọc theo tên
        }

        $stmt = $conn->prepare($sql);
        if (!empty($keyword)) $stmt->bindValue(':key', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}