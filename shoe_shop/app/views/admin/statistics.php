<?php include APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>

        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Báo cáo thống kê</h1>
                <a href="<?php echo URLROOT; ?>/Admin/exportInventory" class="btn btn-success shadow-sm px-4 fw-bold">
                    <i class="fas fa-file-excel me-2"></i> Xuất file
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 py-2 bg-primary text-white" style="min-height: 250px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="text-xs font-weight-bold text-uppercase mb-2" style="letter-spacing: 1px; opacity: 0.9;">Doanh thu trong ngày</div>
                            <div class="h2 mb-0 font-weight-bold">
                                <?php echo number_format($data['today_revenue'], 0, ',', '.'); ?> VNĐ
                            </div>
                            <i class="fas fa-coins mt-3 fa-2x" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white fw-bold border-0 pt-3">
                            <i class="fas fa-chart-line me-2 text-primary"></i>Biểu đồ doanh thu các tháng gần đây
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-boxes me-2"></i>Thống kê tồn kho sản phẩm</h5>
                    <form action="<?php echo URLROOT; ?>/Admin/statistics" method="GET" class="d-flex w-50">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control border-0 shadow-none" 
                                   placeholder="Tìm kiếm mã hoặc tên sản phẩm..." value="<?php echo $data['search']; ?>">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-0 text-center mb-0">
                            <thead class="table-light text-uppercase small fw-bold">
                                <tr>
                                    <th class="py-3">Mã sản phẩm</th>
                                    <th class="text-start">Tên sản phẩm</th>
                                    <th>Số lượng tồn kho</th>
                                    <th class="text-end px-4">Giá trị tồn kho (VNĐ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['inventory'])): ?>
                                    <?php foreach($data['inventory'] as $item): ?>
                                    <tr>
                                        <td class="text-muted">#<?php echo $item['id']; ?></td>
                                        <td class="text-start fw-bold text-dark"><?php echo $item['product_name']; ?></td>
                                        <td>
                                            <span class="badge <?php echo ($item['stock_quantity'] > 10) ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger'; ?> rounded-pill px-3">
                                                <?php echo $item['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td class="text-end text-danger fw-bold px-4">
                                            <?php echo number_format($item['inventory_value'], 0, ',', '.'); ?>đ
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="py-5 text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3 d-block opacity-20"></i>
                                            Không tìm thấy sản phẩm phù hợp.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-3">
                    <nav>
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?php echo ($data['page'] <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link border-0 rounded-circle mx-1" href="?page=<?php echo $data['page'] - 1; ?>&search=<?php echo $data['search']; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link border-0 rounded-circle mx-1 shadow-sm" href="#"><?php echo $data['page']; ?></a>
                            </li>
                            <li class="page-item">
                                <a class="page-link border-0 rounded-circle mx-1" href="?page=<?php echo $data['page'] + 1; ?>&search=<?php echo $data['search']; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const months = [<?php foreach($data['monthly_revenue'] as $row) echo "'Tháng " . $row['month'] . "',"; ?>];
    const totals = [<?php foreach($data['monthly_revenue'] as $row) echo $row['total'] . ","; ?>];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: totals,
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                hoverBackgroundColor: 'rgba(13, 110, 253, 1)',
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: true, drawBorder: false, color: '#f0f0f0' } },
                x: { grid: { display: false } }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

<style>
    .bg-soft-success { background-color: #e8f5e9; color: #2e7d32; }
    .bg-soft-danger { background-color: #ffebee; color: #c62828; }
    .page-link { color: #333; transition: all 0.2s; }
    .page-item.active .page-link { background-color: #0d6efd !important; color: #fff !important; }
    .card { transition: transform 0.2s; }
</style>

<?php include APPROOT . '/views/layout/footer.php'; ?>