<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-uppercase">Quản lý đánh giá</h1>
            </div>

            <ul class="nav nav-tabs mb-4 border-bottom-0" id="reviewTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#detail-reviews">Đánh giá từ khách hàng</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold text-secondary" data-bs-toggle="tab" data-bs-target="#stats-reviews">Thống kê đánh giá</button>
                </li>
            </ul>

            <div class="tab-content border bg-white p-4 shadow-sm rounded">
                <div class="tab-pane fade show active" id="detail-reviews">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Người mua</th>
                                <th>Nội dung</th>
                                <th class="text-center">Số sao</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['reviews'] as $review): ?>
                            <tr>
                                <td class="fw-bold"><?php echo $review['product_name']; ?></td>
                                <td><?php echo $review['customer_name']; ?></td>
                                <td class="small text-muted"><?php echo $review['content']; ?></td>
                                <td class="text-center text-warning">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $review['rating']) ? '★' : '☆'; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="if(confirm('Xóa đánh giá này?')) location.href='<?php echo URLROOT; ?>/Admin/deleteReview/<?php echo $review['id']; ?>'"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="stats-reviews">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-start">Tên sản phẩm</th>
                                <th>Tổng số lượt mua</th>
                                <th>Số sao trung bình</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['stats'] as $stat): ?>
                            <tr>
                                <td class="text-start fw-bold"><?php echo $stat['product_name']; ?></td>
                                <td><?php echo $stat['total_sales']; ?> lượt</td>
                                <td>
                                    <span class="badge bg-warning text-dark px-3">
                                        <?php echo number_format($stat['avg_rating'], 1); ?> / 5.0
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include APPROOT . '/views/layout/footer.php'; ?>