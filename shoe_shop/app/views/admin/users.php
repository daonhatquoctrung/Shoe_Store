<?php include APPROOT . '/views/layout/admin_header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include APPROOT . '/views/layout/admin_sidebar.php'; ?>
        <main class="col-md-10 ms-sm-auto px-md-4 pt-4 bg-light min-vh-100">
            <h1 class="h2 fw-bold text-uppercase border-bottom pb-2 mb-4">Quản lý người dùng</h1>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/Admin/users" method="GET" class="row g-3">
                        <input type="hidden" name="tab" value="<?php echo $data['current_tab']; ?>">
                        <div class="col-md-5">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc mã..." value="<?php echo $data['keyword']; ?>">
                        </div>
                        <?php if($data['current_tab'] == 'customers'): ?>
                        <div class="col-md-3">
                            <select name="filter" class="form-select" onchange="this.form.submit()">
                                <option value="name" <?php echo ($data['filter'] == 'name') ? 'selected' : ''; ?>>Lọc theo tên (A-Z)</option>
                                <option value="id" <?php echo ($data['filter'] == 'id') ? 'selected' : ''; ?>>Lọc theo mã khách hàng</option>
                                <option value="purchase" <?php echo ($data['filter'] == 'purchase') ? 'selected' : ''; ?>>Lọc theo số lần mua</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($data['current_tab'] == 'employees') ? 'active' : 'bg-white border'; ?>" 
                       href="<?php echo URLROOT; ?>/Admin/users?tab=employees">Quản lý nhân viên</a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link <?php echo ($data['current_tab'] == 'customers') ? 'active' : 'bg-white border'; ?>" 
                       href="<?php echo URLROOT; ?>/Admin/users?tab=customers">Quản lý khách hàng</a>
                </li>
            </ul>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <?php if($data['current_tab'] == 'employees'): ?>
                                <tr>
                                    <th>Mã nhân viên</th>
                                    <th>Họ tên nhân viên</th>
                                    <th>Số điện thoại</th>
                                    <th>Quyền hạn / Vai trò</th>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th>Mã khách hàng</th>
                                    <th>Họ tên khách hàng</th>
                                    <th>Số lần mua hàng</th>
                                </tr>
                            <?php endif; ?>
                        </thead>
                        <tbody>
                            <?php 
                            $list = ($data['current_tab'] == 'employees') ? $data['employees'] : $data['customers'];
                            if(!empty($list)): 
                                foreach($list as $user): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td class="text-start fw-bold"><?php echo $user['full_name']; ?></td>
                                    <?php if($data['current_tab'] == 'employees'): ?>
                                        <td><?php echo $user['phone']; ?></td>
                                        <td><span class="badge <?php echo ($user['role'] == 'admin') ? 'bg-danger' : 'bg-warning text-dark'; ?>"><?php echo strtoupper($user['role']); ?></span></td>
                                    <?php else: ?>
                                        <td><span class="badge bg-info text-dark"><?php echo $user['purchase_count']; ?> lần mua</span></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; 
                            else: ?>
                                <tr><td colspan="4" class="py-4 text-muted">Không tìm thấy người dùng phù hợp.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include APPROOT . '/views/layout/footer.php'; ?>