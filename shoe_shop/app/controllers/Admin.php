<?php
class Admin extends Controller {
    private $productModel;
    private $orderModel;
    private $paymentModel;
    private $promotionModel;
    private $adModel;
    private $reviewModel;
    private $userModel;

    public function __construct() {
        // Kiểm tra quyền: Chỉ cho phép tài khoản Admin truy cập trang này
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: " . URLROOT . "/Auth/login");
            exit();
        }
        
        // Khởi tạo các Model để xử lý dữ liệu tương ứng
        $this->productModel   = $this->model('ProductModel');
        $this->orderModel     = $this->model('OrderModel');
        $this->paymentModel   = $this->model('PaymentModel');
        $this->promotionModel = $this->model('PromotionModel');
        $this->adModel        = $this->model('AdModel');
        $this->reviewModel    = $this->model('ReviewModel');
        $this->userModel      = $this->model('UserModel');
    }

    public function index() { $this->orders(); }
    public function dashboard() { $this->orders(); }

    // Quản lý đơn hàng: Tìm kiếm, phân trang và lọc theo trạng thái (tab)
    public function orders() {
        $tab = $_GET['tab'] ?? 'Hiện tại';
        $search = trim($_GET['search'] ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getOrdersPaged($tab, $search, $limit, $offset);

        $data = [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'current_tab' => $tab,
            'search' => $search,
            'page' => $page
        ];
        $this->view('admin/orders', $data);
    }

    // Cập nhật trạng thái đơn hàng (Ví dụ: Xác nhận đơn hoặc Hủy đơn)
    public function updateOrderStatus($id) {
        $newStatus = $_GET['status'] ?? ''; 
        $currentTab = $_GET['old_tab'] ?? 'Hiện tại';

        if ($this->orderModel->updateStatus($id, $newStatus)) {
            header("Location: " . URLROOT . "/Admin/orders?tab=" . urlencode($currentTab));
        } else {
            $_SESSION['error'] = "Cập nhật thất bại";
            header("Location: " . URLROOT . "/Admin/orders?tab=" . urlencode($currentTab));
        }
        exit();
    }

    // Xuất dữ liệu đơn hàng ra file CSV theo trạng thái đang chọn
    public function exportOrders() {
        $status = $_GET['tab'] ?? 'Hiện tại';
        $orders = $this->orderModel->getAllOrdersForExport($status);
        
        $fileName = "Danh_sach_don_hang_" . str_replace(' ', '_', $status) . "_" . date('d-m-Y') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); 
        fputcsv($output, ['Mã đơn hàng', 'Tên sản phẩm', 'Số lượng', 'Tên người mua', 'Ngày mua']);

        if(!empty($orders)) {
            foreach ($orders as $row) {
                fputcsv($output, [
                    $row['id'],
                    $row['product_name'],
                    $row['quantity'],
                    $row['customer_name'],
                    date('d/m/Y H:i', strtotime($row['order_date']))
                ]);
            }
        }
        fclose($output);
        exit();
    }

    // Hàm nội bộ lấy dữ liệu tổng hợp cho Sản phẩm, Danh mục và Kho hàng
    private function getGoodsData($currentTab) {
        return [
            'title'       => 'Quản lý hàng hóa',
            'current_tab' => $currentTab,
            'products'    => $this->productModel->getAllProducts(),
            'categories'  => $this->productModel->getCategories(),
            'inventory'   => $this->productModel->getInventoryData()
        ];
    }
    
    // Các trang hiển thị danh sách Sản phẩm, Danh mục và Tồn kho
    public function products() { $data = $this->getGoodsData('products'); $this->view('admin/products', $data); }
    public function categories() { $data = $this->getGoodsData('categories'); $this->view('admin/products', $data); }
    public function stock() { $data = $this->getGoodsData('stock'); $this->view('admin/products', $data); }

    // Các hàm phục vụ Thêm và Sửa thông tin Sản phẩm
    public function addProduct() {
        $data = ['title' => 'Quản lý hàng hóa', 'categories' => $this->productModel->getCategories()];
        $this->view('admin/products/add', $data);
    }
    public function editProduct($id) {
        $data = [
            'title' => 'Quản lý hàng hóa',
            'product' => $this->productModel->getProductById($id),
            'categories' => $this->productModel->getCategories()
        ];
        $this->view('admin/products/edit', $data);
    }
    
    // Các hàm phục vụ Thêm và Sửa thông tin Danh mục
    public function addCategory() { $this->view('admin/categories/add', ['title' => 'Quản lý hàng hóa']); }
    public function editCategory($id) {
        $category = $this->productModel->getCategoryById($id);
        $this->view('admin/categories/edit', ['title' => 'Quản lý hàng hóa', 'category' => $category]);
    }

    // Quản lý thanh toán: Xem danh sách và tìm kiếm giao dịch
    public function payments() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $payments = !empty($keyword) ? $this->paymentModel->searchPayments($keyword) : $this->paymentModel->getAllPayments();
        $this->view('admin/payments', ['title' => 'Quản lý thanh toán', 'payments' => $payments, 'keyword' => $keyword]);
    }
    
    // Xử lý hoàn tiền cho khách hàng
    public function refund($paymentId) {
        if ($this->paymentModel->checkRefundEligibility($paymentId)) {
            $result = $this->paymentModel->processRefund($paymentId);
            $_SESSION['payment_message'] = $result ? "Hoàn tiền thành công" : "Lỗi kết nối cơ sở dữ liệu";
        } else {
            $_SESSION['payment_message'] = "Hoàn tiền thất bại";
        }
        header("Location: " . URLROOT . "/Admin/payments"); exit();
    }

    // Quản lý khuyến mãi: Xem danh sách và thống kê mã giảm giá
    public function promotions() {
        $data = ['title' => 'Quản lý khuyến mãi', 'promotions' => $this->promotionModel->getAllPromotions(), 'stats' => $this->promotionModel->getPromotionStats()];
        $this->view('admin/promotions', $data);
    }
    
    // Hiển thị form thêm mã khuyến mãi
    public function addPromotion() { $this->view('admin/promotions/add', ['title' => 'Quản lý khuyến mãi']); }
    
    // Lưu mã khuyến mãi mới vào cơ sở dữ liệu
    public function storePromotion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['code' => trim($_POST['code']), 'apply_to' => trim($_POST['apply_to']), 'discount_percent' => $_POST['discount_percent'], 'start_date' => $_POST['start_date'], 'end_date' => $_POST['end_date']];
            if ($this->promotionModel->addPromotion($data)) { header("Location: " . URLROOT . "/Admin/promotions"); } 
            else { $_SESSION['promo_error'] = "Không thể thêm mã"; $this->addPromotion(); }
        }
    }
    
    // Các hàm phục vụ Sửa và Xóa mã khuyến mãi
    public function editPromotion($id) {
        $promotion = $this->promotionModel->getPromotionById($id);
        $this->view('admin/promotions/edit', ['title' => 'Quản lý khuyến mãi', 'promotion' => $promotion]);
    }
    public function updatePromotion($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['code' => trim($_POST['code']), 'apply_to' => trim($_POST['apply_to']), 'discount_percent' => $_POST['discount_percent'], 'start_date' => $_POST['start_date'], 'end_date' => $_POST['end_date']];
            if ($this->promotionModel->updatePromotion($id, $data)) { header("Location: " . URLROOT . "/Admin/promotions"); }
            else { $_SESSION['promo_error'] = "Lỗi cập nhật"; $this->editPromotion($id); }
        }
    }
    public function deletePromotion($id) {
        $this->promotionModel->deletePromotion($id);
        header("Location: " . URLROOT . "/Admin/promotions"); exit();
    }

    // Quản lý quảng cáo: Hiển thị danh sách banner hoặc ảnh quảng cáo
    public function ads() {
        $type = $_GET['type'] ?? 'banner';
        $data = ['title' => 'Quản lý quảng cáo', 'current_type' => $type, 'ads' => $this->adModel->getAdsByType($type)];
        $this->view('admin/ads', $data);
    }
    
    // Lưu quảng cáo mới (có xử lý tải ảnh lên thư mục server)
    public function storeAd() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = $_POST['type']; $targetDir = "public/img/ads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = basename($_FILES["ad_image"]["name"]);
            if (move_uploaded_file($_FILES["ad_image"]["tmp_name"], $targetDir . $fileName)) {
                $this->adModel->addAd(['title' => trim($_POST['title']), 'image_url' => $fileName, 'type' => $type]);
            }
            header("Location: " . URLROOT . "/Admin/ads?type=" . $type); exit();
        }
    }
    
    // Xóa quảng cáo
    public function deleteAd($id) {
        $this->adModel->deleteAd($id);
        header("Location: " . URLROOT . "/Admin/ads?type=" . ($_GET['type'] ?? 'banner')); exit();
    }

    // Quản lý đánh giá: Xem danh sách đánh giá của khách hàng và xóa nếu cần
    public function reviews() {
        $data = ['title' => 'Quản lý đánh giá', 'reviews' => $this->reviewModel->getAllReviews(), 'stats' => $this->reviewModel->getReviewStats()];
        $this->view('admin/reviews', $data);
    }
    public function deleteReview($id) {
        $this->reviewModel->deleteReview($id);
        header("Location: " . URLROOT . "/Admin/reviews"); exit();
    }

    // Quản lý người dùng: Phân tách quản lý Nhân viên và Khách hàng
    public function users() {
        $tab = $_GET['tab'] ?? 'employees';
        $keyword = trim($_GET['keyword'] ?? '');
        $filter = $_GET['filter'] ?? 'name';
        $data = [
            'title' => 'Quản lý người dùng', 'current_tab' => $tab, 'keyword' => $keyword, 'filter' => $filter,
            'employees' => ($tab == 'employees') ? $this->userModel->getEmployees($keyword) : [],
            'customers' => ($tab == 'customers') ? $this->userModel->getCustomers($keyword, $filter) : []
        ];
        $this->view('admin/users', $data);
    }

    // Báo cáo thống kê: Doanh thu ngày, tháng và tình trạng tồn kho sản phẩm
    public function statistics() {
        $search = trim($_GET['search'] ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10; $offset = ($page - 1) * $limit;
        $data = [
            'title' => 'Báo cáo thống kê', 'search' => $search, 'page' => $page,
            'today_revenue' => $this->productModel->getTodayRevenue(),
            'monthly_revenue' => $this->productModel->getMonthlyRevenue(),
            'inventory' => $this->productModel->getInventoryPaged($search, $limit, $offset)
        ];
        $this->view('admin/statistics', $data);
    }

    // Xuất file CSV báo cáo tồn kho hiện tại
    public function exportInventory() {
        $inventory = $this->productModel->getInventoryData();
        $fileName = "Bao_cao_ton_kho_" . date('d-m-Y') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['Mã sản phẩm', 'Tên sản phẩm', 'Số lượng tồn', 'Giá trị tồn kho (VNĐ)']);
        if(!empty($inventory)) {
            foreach ($inventory as $item) {
                fputcsv($output, [
                    $item['id'] ?? 'N/A', 
                    $item['product_name'], 
                    $item['stock_quantity'], 
                    number_format($item['inventory_value'], 0, ',', '.')
                ]);
            }
        }
        fclose($output); exit();
    }
}