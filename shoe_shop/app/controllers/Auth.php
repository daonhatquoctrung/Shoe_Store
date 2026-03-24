<?php
class Auth extends Controller {
    private $userModel;

    public function __construct() {
        // Khởi tạo model UserModel để thực hiện các thao tác với người dùng
        $this->userModel = $this->model('UserModel');
    }

    // Xử lý Đăng ký: Nhận dữ liệu từ form, lưu vào DB qua Model và chuyển hướng về trang Login
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => trim($_POST['username']),
                'password' => $_POST['password'],
                'full_name' => trim($_POST['full_name']),
                'email'    => trim($_POST['email'])
            ];
            
            if ($this->userModel->register($data)) {
                header("Location: " . URLROOT . "/Auth/login");
                exit();
            }
        }
        $this->view('share/register');
    }

    // Xử lý Đăng nhập: Kiểm tra tài khoản, nếu đúng thì lưu thông tin vào Session và chuyển hướng
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $user = $this->userModel->login($username, $password);

            if ($user) {
                // Lưu thông tin định danh vào Session để dùng cho các trang sau
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Gọi hàm phân quyền chuyển hướng
                $this->redirectByRole($user['role']);
            } else {
                $data = [
                    'error' => 'Sai tài khoản hoặc mật khẩu!',
                    'username' => $username
                ];
                $this->view('share/login', $data);
            }
        } else {
            $this->view('share/login');
        }
    }

    // Phân quyền chuyển hướng: Admin vào Thống kê, Staff vào Đơn hàng, Khách vào Trang chủ
    private function redirectByRole($role) {
        switch ($role) {
            case 'admin':
                header("Location: " . URLROOT . "/Admin/statistics");
                break;
            case 'staff':
                header("Location: " . URLROOT . "/Staff/orders");
                break;
            default:
                header("Location: " . URLROOT . "/Home/index");
                break;
        }
        exit();
    }

    // Xử lý Đăng xuất: Xóa toàn bộ dữ liệu Session và đưa người dùng về trang đăng nhập
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        unset($_SESSION['full_name']);
        session_destroy();
        
        header("Location: " . URLROOT . "/Auth/login");
        exit();
    }
}