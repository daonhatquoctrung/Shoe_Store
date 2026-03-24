<?php include APPROOT . '/views/layout/client_header.php'; ?>

<style>
    body, html {
        height: 100%;
        margin: 0;
        overflow: hidden; 
    }
    .full-screen-container {
        height: 100vh;
        width: 100vw;
        overflow: hidden;
    }
    .login-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container-fluid p-0 full-screen-container">
    <div class="row g-0 h-100">
        <div class="col-md-7 d-none d-md-block">
            <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?q=80&w=1470&auto=format&fit=crop" 
                 alt="Login Image" class="login-image">
        </div>
        
        <div class="col-md-5 d-flex align-items-center bg-white">
            <div class="p-5 w-100">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">CHÀO MỪNG TRỞ LẠI</h2>
                    <p class="text-muted">Đăng nhập để tiếp tục mua sắm tại Shoe Store</p>
                </div>

                <?php if(isset($data['error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm p-2 small"><?php echo $data['error']; ?></div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/Auth/login" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="userInput" placeholder="Username" required>
                        <label for="userInput">Tên đăng nhập</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                        <label for="passInput">Mật khẩu</label>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 small">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ</label>
                        </div>
                        <a href="#" class="text-decoration-none text-primary fw-bold">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill shadow-sm fw-bold">ĐĂNG NHẬP</button>
                </form>

                <p class="text-center mt-4 small">Bạn mới đến lần đầu? 
                    <a href="<?php echo URLROOT; ?>/Auth/register" class="text-primary fw-bold text-decoration-none">Tạo tài khoản ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URLROOT; ?>/public/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include APPROOT . '/views/layout/footer.php'; ?>