<?php include APPROOT . '/views/layout/client_header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center shadow-lg rounded-4 overflow-hidden bg-white mx-1">
        <div class="col-lg-6 p-5">
            <h2 class="fw-bold text-success mb-3">ĐĂNG KÝ THÀNH VIÊN</h2>
            <p class="text-muted mb-4">Nhận ngay ưu đãi giảm giá 10% cho đơn hàng đầu tiên.</p>

            <form action="<?php echo URLROOT; ?>/Auth/register" method="POST">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label small fw-bold">Họ và Tên</label>
                        <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label small fw-bold">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label class="form-label small fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm">BẮT ĐẦU NGAY</button>
            </form>
            
            <p class="text-center mt-4">Đã có tài khoản? 
                <a href="<?php echo URLROOT; ?>/Auth/login" class="text-success fw-bold text-decoration-none">Đăng nhập tại đây</a>
            </p>
        </div>

        <div class="col-lg-6 d-none d-lg-block p-0 position-relative">
            <div class="position-absolute top-50 start-50 translate-middle text-white text-center w-75">
                <h1 class="display-4 fw-bold">Just Do It.</h1>
                <p class="lead">Gia nhập cộng đồng đam mê Sneaker lớn nhất Việt Nam.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1470&auto=format&fit=crop" 
                 class="w-100 h-100" style="object-fit: cover; filter: brightness(0.7);">
        </div>
    </div>
</div>

<?php include APPROOT . '/views/layout/footer.php'; ?>