<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URLROOT; ?>">SHOE STORE</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>">Trang chủ</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])) : ?>
            <li class="nav-item"><a class="nav-link" href="#">Chào, <?php echo $_SESSION['full_name']; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/Auth/logout">Đăng xuất</a></li>
        <?php else : ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/Auth/login">Đăng nhập</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/Auth/register">Đăng ký</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>