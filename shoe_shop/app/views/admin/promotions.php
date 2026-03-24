<?php include APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>

        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Quản lý khuyến mãi</h1>
                <button class="btn btn-primary shadow-sm" onclick="location.href='<?php echo URLROOT; ?>/Admin/addPromotion'">
                    <i class="fas fa-plus-circle me-1"></i> Thêm mã giảm giá
                </button>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <h6 class="text-uppercase small">Còn hoạt động</h6>
                            <h2 class="mb-0 fw-bold"><?php echo $data['stats']['active'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-danger text-white">
                        <div class="card-body">
                            <h6 class="text-uppercase small">Đã hết hạn</h6>
                            <h2 class="mb-0 fw-bold"><?php echo $data['stats']['expired'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã KM</th>
                                <th>Áp dụng cho</th>
                                <th class="text-center">Chiết khấu</th>
                                <th>Thời gian bắt đầu</th>
                                <th>Thời gian kết thúc</th>
                                <th>Tình trạng</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['promotions'])): ?>
                                <?php foreach($data['promotions'] as $promo): ?>
                                <tr>
                                    <td><span class="fw-bold text-primary"><?php echo $promo['code']; ?></span></td>
                                    <td><?php echo $promo['apply_to']; ?></td>
                                    <td class="text-center fw-bold text-danger"><?php echo $promo['discount_percent']; ?>%</td>
                                    <td><?php echo date('d-m-Y', strtotime($promo['start_date'])); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($promo['end_date'])); ?></td>
                                    <td>
                                        <?php 
                                            $today = date('Y-m-d');
                                            if($promo['end_date'] >= $today): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Hết hạn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-warning me-1" title="Sửa"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Xóa mã này?')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center py-3">Chưa có chương trình khuyến mãi nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include APPROOT . '/views/layout/footer.php'; ?>