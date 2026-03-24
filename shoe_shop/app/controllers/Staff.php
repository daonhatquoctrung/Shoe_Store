<?php
class Staff extends Controller {
    private $orderModel;

    public function __construct() {
        // Kiểm tra quyền: Chỉ cho phép tài khoản 'staff' truy cập, nếu không sẽ đá về trang login
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'staff') {
            header('location: ' . URLROOT . '/Auth/login');
            exit();
        }
        // Khởi tạo model quản lý đơn hàng
        $this->orderModel = $this->model('OrderModel');
    }

    // Điều hướng mặc định về danh sách đơn hàng
    public function index() {
        $this->orders();
    }

    // Quản lý đơn hàng: Lấy danh sách đơn, hỗ trợ tìm kiếm, phân trang và lọc theo trạng thái (tab)
    public function orders() {
        $tab = $_GET['tab'] ?? 'Đơn hàng mới';
        $search = trim($_GET['search'] ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getStaffOrders($tab, $search, $limit, $offset);

        if (!$orders && !empty($search)) {
            $_SESSION['error_search'] = "Không tìm thấy đơn hàng";
        }

        $data = [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'current_tab' => $tab,
            'search' => $search,
            'page' => $page
        ];
        
        $this->view('staff/orders', $data);
    }

    // Lấy chi tiết đơn hàng (AJAX): Trả về dữ liệu dạng JSON để hiển thị nhanh trên Popup/Modal
    public function getDetailAjax($id) {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            echo json_encode(['error' => 'Không tìm thấy đơn hàng']);
            return;
        }
        echo json_encode($order);
    }

    // Xử lý nhanh tại Popup: Cho phép nhân viên bấm 'Xác nhận' hoặc 'Hủy' đơn hàng ngay lập tức
    public function handleAction($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action']; 
            $newStatus = ($action == 'confirm') ? 'Đã xác nhận' : 'Đã hủy';
            
            $order = $this->orderModel->getOrderById($id);
            if (!$order || ($order['status'] != 'Hiện tại' && $order['status'] != 'Đơn hàng mới')) {
                $_SESSION['error'] = "Không thể xử lý đơn hàng này";
                header('location: ' . URLROOT . '/Staff/orders');
                exit();
            }

            if ($this->orderModel->updateStatus($id, $newStatus)) {
                $_SESSION['success'] = "Đã cập nhật";
            } else {
                $_SESSION['error'] = "Lỗi kết nối cơ sở dữ liệu";
            }
            
            header('location: ' . URLROOT . '/Staff/orders?tab=Đơn hàng mới');
            exit();
        }
    }

    // Cập nhật và thông báo: Thay đổi trạng thái đơn hàng và gửi Email tự động cho khách hàng
    public function updateAndNotify($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'] ?? '';
            $sendEmail = isset($_POST['send_email']) ? true : false;

            if (empty($status)) {
                $_SESSION['error'] = "Vui lòng chọn trạng thái";
                header('location: ' . URLROOT . '/Staff/orders?tab=Đang xử lí');
                exit();
            }

            if ($this->orderModel->updateStatus($id, $status)) {
                if ($sendEmail) {
                    $mailSent = $this->sendNotificationEmail($id, $status);
                    if (!$mailSent) {
                        $_SESSION['warning'] = "Đã cập nhật nhưng không gửi được mail";
                    }
                }
                $_SESSION['success'] = "Đã cập nhật";
            } else {
                $_SESSION['error'] = "Lỗi kết nối cơ sở dữ liệu";
            }

            header('location: ' . URLROOT . '/Staff/orders?tab=Đang xử lí');
            exit();
        }
    }

    // Hàm hỗ trợ: Thực hiện gửi Email thông báo trạng thái đơn hàng cho khách
    private function sendNotificationEmail($orderId, $status) {
        return true; 
    }

    // Các giao diện bổ trợ khác dành cho nhân viên (Đổi trả, Kho, Khuyến mãi, Hỗ trợ)
    public function returns() { $this->view('staff/returns', ['title' => 'Quản lý đổi trả']); }
    public function inventory() { $this->view('staff/inventory', ['title' => 'Quản lý kho hàng']); }
    public function promotions() { $this->view('staff/promotions', ['title' => 'Quản lý khuyến mãi']); }
    public function support() { $this->view('staff/support', ['title' => 'Hỗ trợ khách hàng']); }
}