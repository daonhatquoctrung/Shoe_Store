<?php
class App {
    // Sửa 'Users' thành 'Auth' vì file của bạn là Auth.php
    protected $currentController = 'Auth'; 
    protected $currentMethod = 'login';    
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // 1. Kiểm tra Controller
        if(isset($url[0])) {
            $controllerName = ucwords($url[0]);
            if(file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController;

        // 2. Kiểm tra Method
        if(isset($url[1])) {
            if(method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // 3. Lấy Params và gọi hàm
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}