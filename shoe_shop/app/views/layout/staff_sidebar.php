<nav class="col-md-2 d-none d-md-block bg-dark sidebar vh-100 p-3 shadow position-fixed">
    <div class="position-sticky">
        <h5 class="text-white mb-4 text-center fw-bold">STAFF PANEL</h5>
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Quản lý đơn hàng') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Staff/orders">
                    <i class="fas fa-shopping-cart me-2"></i> Quản lý đơn hàng
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Quản lý đổi trả') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Staff/returns">
                    <i class="fas fa-undo me-2"></i> Quản lý đổi trả hàng
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Quản lý khuyến mãi') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Staff/promotions">
                    <i class="fas fa-percentage me-2"></i> Quản lý khuyến mãi
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Quản lý kho hàng') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Staff/inventory">
                    <i class="fas fa-warehouse me-2"></i> Quản lý kho hàng
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link text-white <?php echo ($data['title'] == 'Hỗ trợ khách hàng') ? 'active bg-primary rounded' : ''; ?>" 
                   href="<?php echo URLROOT; ?>/Staff/support">
                    <i class="fas fa-headset me-2"></i> Hỗ trợ khách hàng
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