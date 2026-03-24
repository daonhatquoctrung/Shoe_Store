<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Quản lý quảng cáo</h1>
                <div>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addAdModal">
                        <i class="fas fa-plus me-1"></i> Thêm <?php echo ($data['current_type'] == 'banner') ? 'Banner' : 'Ảnh KM'; ?>
                    </button>
                </div>
            </div>

            <ul class="nav nav-pills mb-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($data['current_type'] == 'banner') ? 'active' : 'bg-white border'; ?>" 
                       href="<?php echo URLROOT; ?>/Admin/ads?type=banner">Banner quảng cáo</a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link <?php echo ($data['current_type'] == 'promo_image') ? 'active' : 'bg-white border'; ?>" 
                       href="<?php echo URLROOT; ?>/Admin/ads?type=promo_image">Hình ảnh khuyến mãi</a>
                </li>
            </ul>

            <?php if(isset($_SESSION['ad_error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['ad_error']; unset($_SESSION['ad_error']); ?></div>
            <?php endif; ?>

            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php if(!empty($data['ads'])): ?>
                    <?php foreach($data['ads'] as $ad): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 position-relative ad-card">
                            <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle" 
                                    onclick="if(confirm('Xác nhận xóa?')) location.href='<?php echo URLROOT; ?>/Admin/deleteAd/<?php echo $ad['id']; ?>?type=<?php echo $data['current_type']; ?>'">
                                <i class="fas fa-times"></i>
                            </button>
                            <img src="<?php echo URLROOT; ?>/public/img/ads/<?php echo $ad['image_url']; ?>" class="card-img-top rounded" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <p class="card-text small fw-bold text-center mb-0"><?php echo $ad['title']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">Không có nội dung quảng cáo nào.</div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="addAdModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo URLROOT; ?>/Admin/storeAd" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Tải lên nội dung mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" value="<?php echo $data['current_type']; ?>">
                <div class="mb-3">
                    <label class="form-label">Tiêu đề quảng cáo</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Chọn hình ảnh</label>
                    <input type="file" name="ad_image" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
<?php include APPROOT . '/views/layout/footer.php'; ?>