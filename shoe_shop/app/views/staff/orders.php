<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/staff_sidebar.php'; ?>
        
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase text-primary">Quản lý đơn hàng</h1>
                <div class="text-muted small">Nhân viên: <strong><?php echo $_SESSION['full_name']; ?></strong></div>
            </div>

            <ul class="nav nav-tabs mb-4 border-0">
                <?php 
                $tabs = ['Đơn hàng mới', 'Đang xử lí', 'Hoàn tất']; 
                foreach($tabs as $t): 
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($data['current_tab'] == $t) ? 'active bg-primary text-white border-primary' : 'bg-white text-dark shadow-sm'; ?> rounded-pill px-4 me-2 transition-all" 
                       href="?tab=<?php echo urlencode($t); ?>">
                        <?php echo $t; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/Staff/orders" method="GET" class="input-group">
                        <input type="hidden" name="tab" value="<?php echo $data['current_tab']; ?>">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0 shadow-none" 
                               placeholder="Nhập mã đơn hàng (ID) để tìm kiếm nhanh..." value="<?php echo $data['search']; ?>">
                        <button class="btn btn-primary px-4 fw-bold" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="py-3 px-4">Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th>Tổng tiền</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['orders'])): foreach($data['orders'] as $order): ?>
                            <tr>
                                <td class="px-4 fw-bold text-primary">#<?php echo $order['id']; ?></td>
                                <td class="fw-bold text-dark"><?php echo $order['customer_name']; ?></td>
                                <td class="text-muted"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <?php 
                                        $statusClass = 'bg-secondary';
                                        if ($order['status'] == 'Hiện tại' || $order['status'] == 'Đơn hàng mới') $statusClass = 'bg-warning text-dark';
                                        elseif ($order['status'] == 'Đã xác nhận' || $order['status'] == 'Đang xử lí') $statusClass = 'bg-info text-white';
                                        elseif ($order['status'] == 'Hoàn tất') $statusClass = 'bg-success text-white';
                                        elseif ($order['status'] == 'Đã hủy') $statusClass = 'bg-danger text-white';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?> rounded-pill px-3 py-2">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-danger"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                                <td class="text-center">
                                    <button onclick="openOrderPopup(<?php echo $order['id']; ?>)" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                        <i class="fas fa-eye me-1"></i> Xem/Sửa
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="py-5 text-center text-muted">Không tìm thấy đơn hàng.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="orderActionForm" method="POST" action="">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-invoice me-2"></i>Chi tiết đơn hàng <span id="modalOrderId"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="modalLoading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Đang tải dữ liệu...</p>
                    </div>
                    <div id="modalContent" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-6 border-end">
                                <h6 class="text-uppercase fw-bold text-primary small mb-3">Thông tin khách hàng</h6>
                                <p class="mb-1"><i class="fas fa-user me-2 text-muted"></i><strong id="m_customer"></strong></p>
                                <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i><span id="m_phone">N/A</span></p>
                                <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i><span id="m_address">N/A</span></p>
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <h6 class="text-uppercase fw-bold text-primary small mb-3">Thông tin vận chuyển</h6>
                                <p class="mb-1">Ngày đặt: <strong id="m_date"></strong></p>
                                <p class="mb-1">Thanh toán: <span class="badge bg-light text-dark border" id="m_payment">Tiền mặt</span></p>
                                <p class="mb-0">Trạng thái hiện tại: <span class="badge bg-soft-primary" id="m_status"></span></p>
                            </div>
                        </div>

                        <h6 class="text-uppercase fw-bold text-primary small mb-3">Danh sách sản phẩm</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Đơn giá</th>
                                    </tr>
                                </thead>
                                <tbody id="m_product_list">
                                    </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                        <td class="text-end text-danger fw-bold h5" id="m_total"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small mb-1">Ghi chú xác nhận đơn hàng</label>
                            <textarea class="form-control" name="note" rows="2" placeholder="Nhập ghi chú nếu có..."></textarea>
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="send_email" id="emailSwitch" checked>
                            <label class="form-check-label small fw-bold" for="emailSwitch">Gửi email thông báo trạng thái cho khách hàng</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" id="modalFooter">
                    </div>
            </div>
        </form>
    </div>
</div>

<script>
const modal = new bootstrap.Modal(document.getElementById('orderModal'));

function openOrderPopup(id) {
    document.getElementById('modalOrderId').innerText = '#' + id;
    document.getElementById('modalLoading').style.display = 'block';
    document.getElementById('modalContent').style.display = 'none';
    document.getElementById('modalFooter').innerHTML = '';
    modal.show();

    // Gọi AJAX lấy chi tiết
    fetch('<?php echo URLROOT; ?>/Staff/getDetailAjax/' + id)
    .then(response => response.json())
    .then(data => {
        if(data.error) {
            alert(data.error);
            modal.hide();
            return;
        }

        // Điền dữ liệu
        document.getElementById('m_customer').innerText = data.customer_name;
        document.getElementById('m_date').innerText = data.order_date;
        document.getElementById('m_status').innerText = data.status;
        document.getElementById('m_total').innerText = new Intl.NumberFormat('vi-VN').format(data.total_amount) + 'đ';
        
        // Render danh sách sản phẩm (giả định cấu trúc có product_name)
        document.getElementById('m_product_list').innerHTML = `
            <tr>
                <td>${data.product_name}</td>
                <td class="text-center">${data.quantity}</td>
                <td class="text-end">${new Intl.NumberFormat('vi-VN').format(data.total_amount/data.quantity)}đ</td>
            </tr>
        `;

        // Logic Nút bấm theo tab và trạng thái
        const currentTab = '<?php echo $data['current_tab']; ?>';
        let footerHtml = '';

        if(currentTab === 'Đơn hàng mới') {
            document.getElementById('orderActionForm').action = '<?php echo URLROOT; ?>/Staff/handleAction/' + id;
            footerHtml = `
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" name="action" value="cancel" class="btn btn-outline-danger">Hủy đơn</button>
                <button type="submit" name="action" value="confirm" class="btn btn-success px-4 fw-bold">Xác nhận đơn</button>
            `;
        } else {
            document.getElementById('orderActionForm').action = '<?php echo URLROOT; ?>/Staff/updateAndNotify/' + id;
            footerHtml = `
                <div class="input-group">
                    <select name="status" class="form-select border-primary" required>
                        <option value="">-- Chọn trạng thái mới --</option>
                        <option value="Đang xử lí" ${data.status === 'Đang xử lí' ? 'selected' : ''}>Đang xử lí</option>
                        <option value="Hoàn tất">Hoàn tất</option>
                        <option value="Đã hủy">Đã hủy</option>
                    </select>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Cập nhật & Gửi mail</button>
                </div>
            `;
        }

        document.getElementById('modalFooter').innerHTML = footerHtml;
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
    })
    .catch(err => {
        console.error(err);
        alert("Lỗi kết nối cơ sở dữ liệu");
        modal.hide();
    });
}
</script>

<style>
    .bg-soft-primary { background-color: #e7f1ff; color: #0d6efd; border: 1px solid #cfe2ff; }
    .modal-content { border-radius: 15px; }
    .pagination .page-link { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
</style>

<?php include APPROOT . '/views/layout/footer.php'; ?>