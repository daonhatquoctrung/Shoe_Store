<?php
class Home extends Controller {
    private $productModel;

    public function __construct() {
        // Khởi tạo model sản phẩm
        $this->productModel = $this->model('ProductModel');
    }

    /**
     * TRANG CHỦ: Hiển thị danh sách sản phẩm và bộ lọc
     */
    public function index() {
        // 1. Thu thập dữ liệu lọc từ URL
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category_id'] ?? ''; 
        $min_price = $_GET['min_price'] ?? 0;
        $max_price = $_GET['max_price'] ?? 5000000;

        // 2. Lấy danh sách danh mục để hiển thị lên menu ngang
        $categories = $this->productModel->getCategories();

        // 3. Gọi Model lấy danh sách sản phẩm theo bộ lọc
        $products = $this->productModel->getProducts([
            'search' => $search,
            'category_id' => $category_id,
            'min_price' => $min_price,
            'max_price' => $max_price
        ]);

        // 4. Chuẩn bị dữ liệu cho View
        $data = [
            'title' => 'Trang chủ - Shoe Store',
            'products' => $products,
            'categories' => $categories, 
            'user_name' => $_SESSION['full_name'] ?? 'Khách'
        ];

        // 5. Hiển thị View nội dung chính và footer
        $this->view('customer/index', $data);       
        $this->view('layout/footer', $data);        
    }

    /**
     * TRANG CHI TIẾT SẢN PHẨM: Hiển thị thông tin cụ thể, size, màu, mô tả
     */
    public function detail($id) {
        // 1. Lấy thông tin chi tiết sản phẩm từ Database
        $product = $this->productModel->getProductById($id);
        
        // Nếu không tìm thấy sản phẩm, quay về trang chủ
        if (!$product) {
            header("Location: " . URLROOT . "/Home");
            exit();
        }

        // 2. Chuẩn bị dữ liệu bổ sung (Size, Màu sắc, Breadcrumb)
        $data = [
            'title' => $product['product_name'] . ' - Shoe Store',
            'product' => $product,
            'categories' => $this->productModel->getCategories(),
            'user_name' => $_SESSION['full_name'] ?? 'Khách',
            
            // Dữ liệu mẫu (Có thể lấy từ DB nếu bảng product_variants tồn tại)
            'sizes' => [39, 40, 40.5, 41, 42, 43],
            'colors' => [
                ['name' => 'Đen', 'code' => '#000000'],
                ['name' => 'Xám', 'code' => '#808080'],
                ['name' => 'Trắng', 'code' => '#FFFFFF'],
                ['name' => 'Xanh lá', 'code' => '#008000']
            ]
        ];

        // 3. Hiển thị View chi tiết
        $this->view('customer/detail', $data);
        $this->view('layout/footer', $data);
    }

    /**
     * Xử lý yêu cầu gợi ý tìm kiếm (AJAX)
     */
    public function suggest() {
        $keyword = $_GET['keyword'] ?? '';
        
        if (empty($keyword) || mb_strlen($keyword) < 2) {
            echo json_encode([]);
            exit;
        }

        $results = $this->productModel->searchSuggestions($keyword);

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * CHỨC NĂNG MUA NGAY: Chuyển thẳng tới checkout
     */
    public function buyNow($id) {
        header("Location: " . URLROOT . "/Cart/checkout?product_id=" . $id);
        exit();
    }
}