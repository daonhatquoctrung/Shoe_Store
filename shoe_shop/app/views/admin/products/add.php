<?php include APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>

        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/Admin/products">Quản lý hàng hóa</a></li>
                    <li class="breadcrumb-item active">Thêm sản phẩm mới</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i> NHẬP THÔNG TIN SẢN PHẨM MỚI</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo URLROOT; ?>/Admin/storeProduct" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Mã sản phẩm (String 20) <span class="text-danger">*</span></label>
                                        <input type="text" name="product_code" class="form-control" placeholder="Ví dụ: NIKE-AF1-01" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold">Tên sản phẩm (String 50) <span class="text-danger">*</span></label>
                                        <input type="text" name="product_name" class="form-control" placeholder="Nhập tên giày..." required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            <option value="1">Giày Thể Thao Nam</option>
                                            <option value="2">Giày Chạy Bộ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Giá bán (Decimal) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="price" class="form-control" placeholder="0.00" required>
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả sản phẩm</label>
                                    <textarea name="description" class="form-control" rows="5" placeholder="Thông tin chi tiết về chất liệu, công nghệ..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-4 border-start">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Số lượng tồn kho ban đầu <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" class="form-control" value="0" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hình ảnh sản phẩm</label>
                                    <div class="border rounded p-3 text-center bg-white shadow-sm">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                        <input type="file" name="product_image" class="form-control form-control-sm">
                                        <small class="text-muted d-block mt-2">Định dạng: JPG, PNG, WEBP (Max 2MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo URLROOT; ?>/Admin/products" class="btn btn-outline-secondary px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-5 shadow"><i class="fas fa-save me-2"></i> Lưu sản phẩm</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include APPROOT . '/views/layout/footer.php'; ?>