<?php include APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-4 main-content">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/Home">ShoeStore</a></li>
            <li class="breadcrumb-item"><a href="#">Giày Nike</a></li>
            <li class="breadcrumb-item active"><?php echo $data['product']['product_name']; ?></li>
        </ol>
    </nav>

    <div class="row bg-white p-4 rounded shadow-sm">
        <div class="col-md-6">
            <div class="text-center">
                <img id="main-img" src="<?php echo URLROOT . '/public/assets/images/' . ($data['product']['image_url'] ?? 'no-product.png'); ?>" 
                     class="img-fluid rounded" style="max-height: 450px;">
            </div>
            <div class="d-flex mt-3 justify-content-center">
                <img src="..." class="img-thumbnail me-2" style="width: 80px; cursor: pointer;">
                </div>
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold"><?php echo $data['product']['product_name']; ?></h2>
            <h3 class="text-danger fw-bold mb-4"><?php echo number_format($data['product']['price'], 0, ',', '.'); ?>đ</h3>
            
            <hr>

            <div class="mb-4">
                <label class="fw-bold mb-2 d-block">Chọn Size: <a href="#" class="float-end small fw-normal">Hướng dẫn chọn size</a></label>
                <div class="btn-group" role="group">
                    <?php foreach($data['sizes'] as $size): ?>
                        <input type="radio" class="btn-check" name="size" id="size-<?php echo $size; ?>" autocomplete="off">
                        <label class="btn btn-outline-dark px-3" for="size-<?php echo $size; ?>"><?php echo $size; ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="fw-bold mb-2 d-block">Màu sắc:</label>
                <?php foreach($data['colors'] as $color): ?>
                    <button class="btn btn-sm btn-outline-secondary me-2 px-3"><?php echo $color; ?></button>
                <?php endforeach; ?>
            </div>

            <div class="mb-4 d-flex align-items-center">
                <label class="fw-bold me-3">Số lượng:</label>
                <input type="number" class="form-control" value="1" min="1" max="99" style="width: 80px;">
            </div>

            <div class="d-grid gap-2 d-md-flex mt-4">
                <a href="<?php echo URLROOT; ?>/Home/buyNow/<?php echo $data['product']['id']; ?>" class="btn btn-primary btn-lg px-5 fw-bold text-uppercase">Mua ngay</a>
                <button onclick="addToCart(<?php echo $data['product']['id']; ?>)" class="btn btn-outline-primary btn-lg px-4 fw-bold text-uppercase">Thêm giỏ hàng</button>
                <button class="btn btn-light border ms-2" title="Yêu thích" onclick="toggleFavoriteDetail(this)">
                    <i class="far fa-heart text-danger fs-4"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mt-5 bg-white p-4 rounded shadow-sm">
        <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#desc">Mô tả sản phẩm</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#review">Đánh giá khách hàng</button>
            </li>
        </ul>
        <div class="tab-content p-3 mt-3">
            <div class="tab-pane fade show active" id="desc">
                <?php echo nl2br($data['product']['description'] ?? 'Đang cập nhật nội dung...'); ?>
            </div>
            <div class="tab-pane fade" id="review">
                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFavoriteDetail(btn) {
        const icon = btn.querySelector('i');
        icon.classList.toggle('far');
        icon.classList.toggle('fas');
        alert("Đã thêm vào danh sách yêu thích!");
    }
</script>