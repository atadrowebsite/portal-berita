<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Portal Berita Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin') !== false): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">
    <?php endif; ?>
</head>
<body>
    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin') === false): ?>
        <!-- Frontend Header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">
                    <i class="fas fa-newspaper"></i> Portal Berita
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>">Beranda</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Kategori
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php 
                                $categories = (new Category())->get_all();
                                foreach ($categories as $cat): 
                                ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/category/<?php echo $cat['slug']; ?>"><?php echo $cat['name']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                                <i class="fas fa-search"></i> Cari
                            </a>
                        </li>
                        <?php if (is_logged_in()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin">Dashboard Admin</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/logout">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>/login">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>/register">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>