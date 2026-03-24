<?php include APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>

        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Quản lý hàng hóa</h1>
            </div>

            <ul class="nav nav-tabs mb-4 border-bottom-0" id="goodsTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link <?php echo ($data['current_tab'] == 'products') ? 'active fw-bold' : 'text-secondary'; ?>" 
                            id="product-tab" data-bs-toggle="tab" data-bs-target="#product-content">Quản lý sản phẩm</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link <?php echo ($data['current_tab'] == 'categories') ? 'active fw-bold' : 'text-secondary'; ?>" 
                            id="category-tab" data-bs-toggle="tab" data-bs-target="#category-content">Quản lý danh mục</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link <?php echo ($data['current_tab'] == 'stock') ? 'active fw-bold' : 'text-secondary'; ?>" 
                            id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock-content">Xem tồn kho</button>
                </li>
            </ul>

            <div class="tab-content border bg-white p-4 shadow-sm rounded">
                
                <div class="tab-pane fade <?php echo ($data['current_tab'] == 'products') ? 'show active' : ''; ?>" id="product-content">
                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <div class="input-group w-50 shadow-sm">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Tìm sản phẩm theo mã hoặc tên...">
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" onclick="location.href='<?php echo URLROOT; ?>/Admin/addProduct'">
                                <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover align-middle border text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Tồn kho</th>
                                <th>Giá bán</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['products'])): ?>
                                <?php foreach($data['products'] as $product) : ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td class="fw-bold text-start"><?php echo $product['product_name']; ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo $product['category_name'] ?? 'N/A'; ?></span></td>
                                    <td><?php echo $product['stock_quantity']; ?></td>
                                    <td class="text-primary fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger ms-1"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade <?php echo ($data['current_tab'] == 'categories') ? 'show active' : ''; ?>" id="category-content">
                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <div class="input-group w-50 shadow-sm">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Tìm danh mục...">
                        </div>
                        <button class="btn btn-success"><i class="fas fa-folder-plus me-1"></i> Thêm danh mục</button>
                    </div>
                    <table class="table table-hover align-middle border col-md-8 mx-auto text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['categories'])): ?>
                                <?php foreach($data['categories'] as $category) : ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td class="fw-bold text-start"><?php echo $category['category_name']; ?></td>
                                    <td class="small text-muted text-start"><?php echo $category['description']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-warning">Sửa</button>
                                        <button class="btn btn-sm btn-outline-danger ms-1">Xóa</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade <?php echo ($data['current_tab'] == 'stock') ? 'show active' : ''; ?>" id="stock-content">
                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <div class="input-group w-50 shadow-sm">
                            <input type="text" class="form-control" placeholder="Tìm sản phẩm tồn kho...">
                        </div>
                        <button class="btn btn-dark shadow-sm"><i class="fas fa-file-excel me-1"></i> Xuất file</button>
                    </div>
                    <table class="table table-striped align-middle border text-center">
                        <thead class="table-primary text-white">
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng tồn</th>
                                <th>Giá trị tồn kho (VNĐ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['inventory'])): ?>
                                <?php foreach($data['inventory'] as $item) : ?>
                                <tr>
                                    <td class="text-start"><?php echo $item['product_name']; ?></td>
                                    <td class="fw-bold <?php echo ($item['stock_quantity'] < 10) ? 'text-danger' : ''; ?>">
                                        <?php echo $item['stock_quantity']; ?>
                                    </td>
                                    <td class="text-end fw-bold"><?php echo number_format($item['inventory_value'], 0, ',', '.'); ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center shadow-sm">
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                </ul>
            </nav>
        </main>
    </div>
</div>

<?php include APPROOT . '/views/layout/footer.php'; ?>