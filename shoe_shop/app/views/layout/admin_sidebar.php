<nav class="col-md-2 d-none d-md-block bg-dark sidebar vh-100 p-3 shadow position-fixed">
    <div class="position-sticky">
        <h5 class="text-white mb-4 text-center fw-bold">SHOE STORE</h5>
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Quản lý đơn hàng') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Admin/orders">
                    <i class="fas fa-shopping-cart me-2"></i> Quản lý đơn hàng
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Quản lý hàng hóa') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Admin/products">
                    <i class="fas fa-box me-2"></i> Quản lý hàng hóa
                </a>
            </li>
            <li class="nav-item mb-1"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/Admin/payments"><i class="fas fa-credit-card me-2"></i> Quản lý thanh toán</a></li>
            <li class="nav-item mb-1"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/Admin/promotions"><i class="fas fa-percentage me-2"></i> Quản lý khuyến mãi</a></li>
            <li class="nav-item mb-1"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/Admin/ads"><i class="fas fa-ad me-2"></i> Quản lý quảng cáo</a></li>
            <li class="nav-item mb-1"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/Admin/reviews"><i class="fas fa-star me-2"></i> Quản lý đánh giá</a></li>
            <li class="nav-item mb-1"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/Admin/users"><i class="fas fa-users-cog me-2"></i> Quản lý người dùng</a></li>
            
            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Báo cáo thống kê') ? 'active bg-primary rounded fw-bold' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Admin/statistics">
                    <i class="fas fa-chart-line me-2"></i> Báo cáo thống kê
                </a>
            </li>
            
            <hr class="text-white shadow">
            <li class="nav-item">
                <a class="nav-link text-danger fw-bold" href="<?php echo URLROOT; ?>/Auth/logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
</nav>