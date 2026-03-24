<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Quản lý đơn hàng</h1>
                <a href="<?php echo URLROOT; ?>/Admin/exportOrders?tab=<?php echo urlencode($data['current_tab']); ?>" class="btn btn-success shadow-sm fw-bold">
                    <i class="fas fa-file-download me-2"></i> Xuất file
                </a>
            </div>

            <ul class="nav nav-pills mb-3 shadow-sm bg-white p-2 rounded">
                <?php $tabs = ['Hiện tại', 'Đã xử lý', 'Đã hủy']; 
                foreach($tabs as $t): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($data['current_tab'] == $t) ? 'active shadow-sm' : 'text-dark'; ?>" 
                       href="?tab=<?php echo urlencode($t); ?>"><?php echo $t; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/Admin/orders" method="GET" class="input-group">
                        <input type="hidden" name="tab" value="<?php echo $data['current_tab']; ?>">
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo mã ĐH, tên sản phẩm hoặc khách hàng..." value="<?php echo $data['search']; ?>">
                        <button class="btn btn-primary px-4" type="submit"><i class="fas fa-search me-1"></i> Tìm</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 small text-uppercase fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>Danh sách đơn hàng: <?php echo $data['current_tab']; ?>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light small fw-bold text-uppercase">
                            <tr>
                                <th>Mã ĐH</th>
                                <th class="text-start">Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Người mua</th>
                                <th>Ngày mua</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['orders'])): foreach($data['orders'] as $order): ?>
                            <tr>
                                <td class="text-muted small fw-bold">#<?php echo $order['id']; ?></td>
                                <td class="text-start fw-bold text-dark"><?php echo $order['product_name']; ?></td>
                                <td><span class="badge bg-secondary px-3"><?php echo $order['quantity']; ?></span></td>
                                <td class="fw-bold"><?php echo $order['customer_name']; ?></td>
                                <td class="small"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <?php if($data['current_tab'] == 'Hiện tại'): ?>
                                        <a href="<?php echo URLROOT; ?>/Admin/updateOrderStatus/<?php echo $order['id']; ?>?status=<?php echo urlencode('Đã xử lý'); ?>&old_tab=Hiện tại" 
                                           class="btn btn-sm btn-success rounded-circle me-1" title="Xác nhận xử lý">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/Admin/updateOrderStatus/<?php echo $order['id']; ?>?status=<?php echo urlencode('Đã hủy'); ?>&old_tab=Hiện tại" 
                                           class="btn btn-sm btn-danger rounded-circle" title="Hủy đơn hàng" 
                                           onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-3 fw-normal">Hoàn tất</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="py-5 text-muted">
                                    <i class="fas fa-search-minus fa-3x mb-3 d-block opacity-25"></i>
                                    Không tìm thấy đơn hàng nào trong mục này.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if(!empty($data['orders'])): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($data['page'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm rounded-circle mx-1" href="?page=<?php echo $data['page']-1; ?>&tab=<?php echo $data['current_tab']; ?>&search=<?php echo $data['search']; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link shadow-sm rounded-circle mx-1" href="#"><?php echo $data['page']; ?></a></li>
                    <li class="page-item">
                        <a class="page-link shadow-sm rounded-circle mx-1" href="?page=<?php echo $data['page']+1; ?>&tab=<?php echo $data['current_tab']; ?>&search=<?php echo $data['search']; ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include APPROOT . '/views/layout/footer.php'; ?>