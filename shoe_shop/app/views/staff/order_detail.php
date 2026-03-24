<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/staff_sidebar.php'; ?>
        
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/Staff/orders">Quản lý đơn hàng</a></li>
                    <li class="breadcrumb-item active">Chi tiết đơn hàng #<?php echo $data['order']['id']; ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark">Chi tiết đơn hàng #<?php echo $data['order']['id']; ?></h1>
                <a href="<?php echo URLROOT; ?>/Staff/orders" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Thông tin đơn hàng
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted w-25">Khách hàng:</td>
                                    <td class="fw-bold"><?php echo $data['order']['customer_name']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Sản phẩm:</td>
                                    <td class="fw-bold text-primary"><?php echo $data['order']['product_name']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Số lượng:</td>
                                    <td><span class="badge bg-light text-dark border px-3"><?php echo $data['order']['quantity']; ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Ngày đặt:</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($data['order']['order_date'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tổng thanh toán:</td>
                                    <td class="h4 fw-bold text-danger"><?php echo number_format($data['order']['total_amount'], 0, ',', '.'); ?>đ</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="fas fa-tasks me-2 text-primary"></i>Xử lý nghiệp vụ
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">Trạng thái hiện tại: 
                                <span class="fw-bold text-primary"><?php echo $data['order']['status']; ?></span>
                            </p>
                            
                            <div class="d-grid gap-2">
                                <?php if ($data['order']['status'] == 'Hiện tại' || $data['order']['status'] == 'Đơn hàng mới'): ?>
                                    <a href="<?php echo URLROOT; ?>/Staff/updateStatus/<?php echo $data['order']['id']; ?>?status=Đang xử lí&old_tab=Đơn hàng mới" 
                                       class="btn btn-info text-white fw-bold py-2">
                                        <i class="fas fa-truck me-2"></i> Tiếp nhận xử lý
                                    </a>
                                <?php elseif ($data['order']['status'] == 'Đang xử lí'): ?>
                                    <a href="<?php echo URLROOT; ?>/Staff/updateStatus/<?php echo $data['order']['id']; ?>?status=Hoàn tất&old_tab=Đang xử lí" 
                                       class="btn btn-success fw-bold py-2">
                                        <i class="fas fa-check-circle me-2"></i> Đánh dấu Hoàn tất
                                    </a>
                                <?php endif; ?>
                                
                                <button class="btn btn-outline-danger btn-sm mt-3" onclick="alert('Vui lòng liên hệ Admin để hủy đơn hàng này!')">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Yêu cầu hủy đơn
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include APPROOT . '/views/layout/footer.php'; ?>