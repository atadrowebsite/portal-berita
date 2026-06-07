<?php 
$page_title = 'Beranda';
include BASE_PATH . '/src/views/layouts/header.php'; 
?>

<!-- Flash Messages -->
<?php 
if ($flash = get_flash_message()): 
?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4 text-white">Portal Berita Terpercaya</h1>
                <p class="lead text-white mb-4">Dapatkan informasi terkini dan terpercaya dari seluruh penjuru dunia. Update berita setiap hari untuk Anda.</p>
                <form action="<?php echo BASE_URL; ?>/search" method="GET" class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="q" placeholder="Cari artikel..." required>
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <div class="row">
        <!-- Articles Column -->
        <div class="col-lg-8">
            <h2 class="mb-4">Berita Terbaru</h2>
            
            <?php if (empty($articles)): ?>
                <div class="alert alert-info">Belum ada artikel yang dipublikasikan.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($articles as $article): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm hover-lift">
                                <?php if ($article['image']): ?>
                                    <img src="<?php echo get_image_url($article['image']); ?>" class="card-img-top" alt="<?php echo $article['title']; ?>" style="height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge bg-primary"><?php echo $article['category_name']; ?></span>
                                        <small class="text-muted ms-2"><i class="fas fa-calendar"></i> <?php echo format_date($article['created_at']); ?></small>
                                    </div>
                                    <h5 class="card-title"><a href="<?php echo BASE_URL; ?>/article/<?php echo $article['id']; ?>" class="text-decoration-none"><?php echo $article['title']; ?></a></h5>
                                    <p class="card-text text-muted"><?php echo truncate_text($article['content'], 100); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Oleh: <?php echo $article['author_name']; ?></small>
                                        <a href="<?php echo BASE_URL; ?>/article/<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Kategori</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>/category/<?php echo $cat['slug']; ?>" class="list-group-item list-group-item-action">
                            <?php echo $cat['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Latest Articles -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Artikel Populer</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($latest as $art): ?>
                        <a href="<?php echo BASE_URL; ?>/article/<?php echo $art['id']; ?>" class="list-group-item list-group-item-action py-3">
                            <h6 class="mb-1"><?php echo truncate_text($art['title'], 40); ?></h6>
                            <small class="text-muted">Kategori: <?php echo $art['category_name']; ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/src/views/layouts/footer.php'; ?>