<?php include APPROOT . '/views/layout/header.php'; ?>

<div class="bg-white py-2 border-bottom shadow-sm">
    <div class="container d-flex justify-content-center">
        <a href="<?php echo URLROOT; ?>/Home" class="btn btn-sm btn-outline-dark mx-2 rounded-pill px-4 <?php echo !isset($_GET['category_id']) ? 'active bg-dark text-white' : ''; ?>">Tất cả</a>
        <?php if(!empty($data['categories'])): foreach($data['categories'] as $cat): ?>
            <a href="?category_id=<?php echo $cat['id']; ?>" 
               class="btn btn-sm <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'btn-primary text-white shadow-sm' : 'btn-outline-dark'; ?> mx-2 rounded-pill px-4 transition-all">
                <?php echo $cat['category_name']; ?>
            </a>
        <?php endforeach; endif; ?>
    </div>
</div>

<div class="container mt-4 mb-5">
    <div class="row">
        <aside class="col-md-3">
            <div class="card border-0 shadow-sm p-3 mb-4 sticky-top" style="top: 90px;">
                <h6 class="fw-bold mb-3 border-bottom pb-2 small text-uppercase text-primary text-center">Bộ lọc giá</h6>
                <form action="<?php echo URLROOT; ?>/Home" method="GET">
                    <?php if(isset($_GET['category_id'])): ?>
                        <input type="hidden" name="category_id" value="<?php echo $_GET['category_id']; ?>">
                    <?php endif; ?>
                    <label class="small fw-bold mb-2">Khoảng giá (VNĐ)</label>
                    <input type="number" name="min_price" class="form-control form-control-sm mb-2" placeholder="Từ" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                    <input type="number" name="max_price" class="form-control form-control-sm mb-3" placeholder="Đến" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm text-uppercase">Áp dụng</button>
                </form>
            </div>
        </aside>

        <main class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-uppercase m-0">Sản phẩm mới nhất</h5>
                <small class="text-muted">Tìm thấy <?php echo count($data['products']); ?> sản phẩm</small>
            </div>
            
            <div class="row g-4">
                <?php if(!empty($data['products'])): foreach($data['products'] as $p): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm product-card transition-all">
                        <a href="<?php echo URLROOT; ?>/Home/detail/<?php echo $p['id']; ?>" class="text-decoration-none">
                            <div class="position-relative overflow-hidden bg-light m-2 rounded text-center">
                                <img src="<?php echo URLROOT . '/public/assets/images/' . ($p['image_url'] ?? 'no-product.png'); ?>" 
                                     class="card-img-top p-3" style="height: 200px; object-fit: contain;" alt="">
                                <button class="btn btn-wishlist position-absolute top-0 end-0 m-2 border-0 bg-transparent shadow-none" onclick="event.preventDefault(); toggleFavorite(this);">
                                    <i class="far fa-heart text-danger h5"></i>
                                </button>
                            </div>
                        </a>
                        <div class="card-body pt-0 text-center">
                            <a href="<?php echo URLROOT; ?>/Home/detail/<?php echo $p['id']; ?>" class="text-decoration-none text-dark">
                                <h6 class="card-title text-truncate fw-bold mb-1"><?php echo htmlspecialchars($p['product_name']); ?></h6>
                            </a>
                            <p class="text-danger fw-bold h5 mb-3"><?php echo number_format($p['price'], 0, ',', '.'); ?>đ</p>
                            <div class="d-grid gap-2">
                                <a href="<?php echo URLROOT; ?>/Home/detail/<?php echo $p['id']; ?>" class="btn btn-primary btn-sm fw-bold py-2 shadow-sm text-uppercase">Mua Ngay</a>
                                <button onclick="addToCart(<?php echo $p['id']; ?>)" class="btn btn-outline-primary btn-sm fw-bold py-2 shadow-sm text-uppercase">Thêm Giỏ Hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; else: ?>
                    <div class="text-center py-5 col-12 bg-white rounded shadow-sm">
                        <p class="text-muted">Không tìm thấy sản phẩm phù hợp.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
    // JS Logic
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const urlRoot = '<?php echo URLROOT; ?>';

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 1) {
                fetch(`${urlRoot}/Home/suggest?keyword=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(p => {
                                const priceFormatted = new Intl.NumberFormat('vi-VN').format(p.price);
                                html += `
                                    <a href="${urlRoot}/Home/detail/${p.id}" class="search-item text-decoration-none">
                                        <img src="${urlRoot}/public/assets/images/${p.image_url || 'no-product.png'}" alt="">
                                        <div class="info">
                                            <p class="name text-dark fw-bold mb-0" style="font-size: 0.9rem;">${p.product_name}</p>
                                            <span class="price text-danger fw-bold small">${priceFormatted}đ</span>
                                        </div>
                                    </a>
                                `;
                            });
                            searchResults.innerHTML = html;
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.style.display = 'none';
                        }
                    })
            } else {
                searchResults.style.display = 'none';
            }
        });
    }

    function addToCart(id) {
        let badge = document.getElementById('cart-badge');
        if(badge) badge.innerText = parseInt(badge.innerText) + 1;
        alert("Đã thêm sản phẩm vào giỏ hàng!");
    }

    function toggleFavorite(btn) {
        let icon = btn.querySelector('i');
        icon.classList.toggle('far');
        icon.classList.toggle('fas');
    }
</script>

<?php include APPROOT . '/views/layout/footer.php'; ?>