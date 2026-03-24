<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4">
            <h2 class="fw-bold border-bottom pb-2">THÊM MÃ GIẢM GIÁ MỚI</h2>
            
            <?php if(isset($_SESSION['promo_error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['promo_error']; unset($_SESSION['promo_error']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm p-4 mt-3">
                <form action="<?php echo URLROOT; ?>/Admin/storePromotion" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mã khuyến mãi</label>
                            <input type="text" name="code" class="form-control" placeholder="Ví dụ: KM2026" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Hãng áp dụng</label>
                            <input type="text" name="apply_to" class="form-control" placeholder="Ví dụ: Nike, Adidas..." required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Phần trăm chiết khấu (%)</label>
                            <input type="number" name="discount_percent" class="form-control" min="1" max="100" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Ngày kết thúc</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary px-4">Lưu mã giảm giá</button>
                        <a href="<?php echo URLROOT; ?>/Admin/promotions" class="btn btn-outline-secondary px-4">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<?php include APPROOT . '/views/layout/footer.php'; ?>