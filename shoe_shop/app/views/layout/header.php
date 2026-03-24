<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/style.css">
</head>
<body class="bg-light">

<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="<?php echo URLROOT; ?>/Home">SHOE STORE</a>
            
            <div class="search-container">
                <form action="<?php echo URLROOT; ?>/Home" method="GET" id="search-form">
                    <div class="input-group">
                        <input class="form-control border-end-0 py-2" type="search" name="search" 
                               id="search-input" placeholder="Bạn muốn tìm đôi giày nào?..." 
                               autocomplete="off" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <button class="btn btn-outline-primary border-start-0 px-4" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <div id="search-results"></div>
            </div>
            
            <div class="d-flex align-items-center">
                <a href="#" class="nav-link nav-icon-link position-relative me-3" title="Thông báo">
                    <i class="far fa-bell fa-lg"></i>
                    <span class="badge rounded-pill bg-danger badge-notify">2</span>
                </a>
                <a href="#" class="nav-link nav-icon-link me-3" title="Sản phẩm yêu thích">
                    <i class="far fa-heart fa-lg"></i>
                </a>
                <a href="#" class="nav-link nav-icon-link position-relative me-3" title="Giỏ hàng">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span id="cart-badge" class="badge rounded-pill bg-primary badge-notify">0</span>
                </a>

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" id="userMenu" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?php echo $data['user_name']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-id-card me-2 text-muted"></i>Tài khoản</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-shopping-bag me-2 text-muted"></i>Đơn mua</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/Auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>