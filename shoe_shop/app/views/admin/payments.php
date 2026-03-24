<?php include APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>

        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Lịch sử thanh toán</h1>
                <?php if(isset($_SESSION['payment_message'])): ?>
                    <div class="alert alert-info py-1 px-3 mb-0 shadow-sm">
                        <?php echo $_SESSION['payment_message']; unset($_SESSION['payment_message']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/Admin/searchPayments" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" name="keyword" class="form-control" placeholder="Nhập mã thanh toán hoặc thông tin đơn hàng...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã thanh toán</th>
                                <th>Tên đơn hàng / Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th>Người mua</th>
                                <th>Ngày mua</th>
                                <th>Thành giá</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['payments'])): ?>
                                <?php foreach($data['payments'] as $payment): ?>
                                <tr>
                                    <td><span class="fw-bold text-primary"><?php echo $payment['payment_code']; ?></span></td>
                                    <td><?php echo $payment['order_name']; ?></td>
                                    <td class="text-center"><?php echo $payment['quantity']; ?></td>
                                    <td><?php echo $payment['customer_name']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($payment['created_at'])); ?></td>
                                    <td class="fw-bold text-success"><?php echo number_format($payment['total_amount'], 0, ',', '.'); ?>đ</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger shadow-sm px-3" 
                                                onclick="return confirm('Xác nhận hoàn tiền cho giao dịch này?')">
                                            <i class="fas fa-undo me-1"></i> Hoàn tiền
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Không có kết quả
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                </ul>
            </nav>
        </main>
    </div>
</div>

<?php include APPROOT . '/views/layout/footer.php'; ?>