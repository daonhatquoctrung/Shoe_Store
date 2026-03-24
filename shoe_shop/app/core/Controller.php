<?php
class Controller {
    // Hàm dùng để gọi Model
    // Ví dụ: $this->model('UserModel');
    public function model($model) {
        $modelPath = '../app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;

            // Kiểm tra class có tồn tại không
            if (class_exists($model)) {
                return new $model();
            } else {
                die("Class $model không tồn tại trong file.");
            }
        } else {
            die("Model $model không tồn tại.");
        }
    }

    // Hàm dùng để gọi View và truyền dữ liệu sang View
    // Ví dụ: $this->view('share/login', $data);
    public function view($view, $data = []) {
        $viewPath = '../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            // Biến $data sẽ được extract ra biến để view sử dụng trực tiếp
            extract($data);
            require_once $viewPath;
        } else {
            die("View $view không tồn tại.");
        }
    }

    // Hàm kiểm tra quyền truy cập (Middleware)
    // Hỗ trợ một hoặc nhiều role
    // $role có thể là string hoặc mảng
    public function middleware($role) {
        if (!isset($_SESSION['user_role'])) {
            // Chưa đăng nhập
            header("Location: " . URLROOT . "/Auth/login?error=Chưa đăng nhập");
            exit();
        }

        // Nếu $role là mảng, kiểm tra có quyền trong mảng không
        if (is_array($role)) {
            if (!in_array($_SESSION['user_role'], $role)) {
                header("Location: " . URLROOT . "/Auth/login?error=Không có quyền truy cập");
                exit();
            }
        } else {
            // Nếu $role là string
            if ($_SESSION['user_role'] !== $role) {
                header("Location: " . URLROOT . "/Auth/login?error=Không có quyền truy cập");
                exit();
            }
        }
    }
}
