<?php
class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // =========================================================================
    // 1. DÀNH CHO KHÁCH HÀNG (TRANG CHỦ, TÌM KIẾM)
    // =========================================================================

    // Lấy danh sách sản phẩm theo các bộ lọc (Search, Danh mục, Giá...)
    public function getProductsForCustomer($filters = []) {
        $conn = $this->db->getConnection();
        $sql = "SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";

        if (!empty($filters['search'])) $sql .= " AND (p.product_name LIKE :search OR p.description LIKE :search)";
        if (!empty($filters['category_id'])) $sql .= " AND p.category_id = :cat_id";
        if (!empty($filters['brand'])) $sql .= " AND p.brand = :brand";
        if (!empty($filters['min_price'])) $sql .= " AND p.price >= :min_price";
        if (!empty($filters['max_price'])) $sql .= " AND p.price <= :max_price";

        $sql .= " ORDER BY p.id DESC";
        $stmt = $conn->prepare($sql);

        if (!empty($filters['search'])) $stmt->bindValue(':search', '%' . $filters['search'] . '%');
        if (!empty($filters['category_id'])) $stmt->bindValue(':cat_id', $filters['category_id'], PDO::PARAM_INT);
        if (!empty($filters['brand'])) $stmt->bindValue(':brand', $filters['brand']);
        if (!empty($filters['min_price'])) $stmt->bindValue(':min_price', (int)$filters['min_price'], PDO::PARAM_INT);
        if (!empty($filters['max_price'])) $stmt->bindValue(':max_price', (int)$filters['max_price'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hàm móc nối để Home Controller gọi không bị lỗi
    public function getProducts($filters = []) {
        return $this->getProductsForCustomer($filters);
    }

    // Tìm kiếm nhanh gợi ý tên và ảnh sản phẩm
    public function searchSuggestions($keyword) {
        $conn = $this->db->getConnection();
        $sql = "SELECT id, product_name, price, image_url FROM products WHERE product_name LIKE :keyword LIMIT 8";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================================
    // 2. QUẢN LÝ CHI TIẾT & BIẾN THỂ
    // =========================================================================

    // Lấy chi tiết 1 sản phẩm kèm tên danh mục
    public function getProductById($id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy kích cỡ sản phẩm (Size)
    public function getProductSizes($product_id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT DISTINCT size FROM product_variants WHERE product_id = ? AND stock_quantity > 0");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); 
    }

    // Lấy màu sắc sản phẩm
    public function getProductColors($product_id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT DISTINCT color_name, color_code FROM product_variants WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy đánh giá của khách hàng cho sản phẩm
    public function getProductReviews($product_id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT r.*, u.full_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================================
    // 3. QUẢN TRỊ ADMIN (THÀNH VIÊN, DANH MỤC, THỐNG KÊ)
    // =========================================================================

    // Lấy toàn bộ sản phẩm cho bảng quản trị
    public function getAllProducts() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách danh mục
    public function getCategories() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT id, category_name, description FROM categories ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Doanh thu hôm nay
    public function getTodayRevenue() {
        $conn = $this->db->getConnection();
        $sql = "SELECT SUM(total_amount) as total FROM payments WHERE DATE(created_at) = CURDATE()";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Doanh thu tháng này
    public function getMonthlyRevenue() {
        $conn = $this->db->getConnection();
        $sql = "SELECT SUM(total_amount) as total FROM payments WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Dữ liệu kho hàng (tổng quan)
    public function getInventoryData() {
        $conn = $this->db->getConnection();
        $sql = "SELECT id, product_name, stock_quantity, (stock_quantity * price) as inventory_value FROM products ORDER BY stock_quantity DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Dữ liệu kho hàng (có phân trang và tìm kiếm cho giao diện Admin)
    public function getInventoryPaged($search, $limit, $offset) {
        $conn = $this->db->getConnection();
        $sql = "SELECT id, product_name, stock_quantity, (stock_quantity * price) as inventory_value 
                FROM products 
                WHERE product_name LIKE :search 
                ORDER BY stock_quantity DESC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}