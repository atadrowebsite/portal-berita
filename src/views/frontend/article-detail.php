<?php 
$page_title = $article['title'];
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

<!-- Breadcrumb -->
<div class="container py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/category/<?php echo generate_slug($article['category_name']); ?>"><?php echo $article['category_name']; ?></a></li>
            <li class="breadcrumb-item active"><?php echo truncate_text($article['title'], 50); ?></li>
        </ol>
    </nav>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Article Content -->
        <div class="col-lg-8">
            <article>
                <div class="mb-4">
                    <h1 class="display-5 fw-bold mb-3"><?php echo $article['title']; ?></h1>
                    <div class="d-flex flex-wrap gap-3 border-bottom pb-3">
                        <div>
                            <span class="badge bg-primary"><?php echo $article['category_name']; ?></span>
                        </div>
                        <div>
                            <i class="fas fa-user"></i> <strong>Oleh:</strong> <?php echo $article['author_name']; ?>
                        </div>
                        <div>
                            <i class="fas fa-calendar"></i> <strong>Tanggal:</strong> <?php echo format_date($article['created_at'], 'd M Y H:i'); ?>
                        </div>
                        <div>
                            <i class="fas fa-clock"></i> <strong>Update:</strong> <?php echo time_ago($article['updated_at']); ?>
                        </div>
                    </div>
                </div>

                <?php if ($article['image']): ?>
                    <img src="<?php echo get_image_url($article['image']); ?>" alt="<?php echo $article['title']; ?>" class="img-fluid rounded mb-4" style="max-height: 500px; width: 100%; object-fit: cover;">
                <?php endif; ?>

                <div class="article-content lead">
                    <?php echo nl2br($article['content']); ?>
                </div>
            </article>

            <!-- Comments Section -->
            <section class="mt-5 pt-5 border-top">
                <h3 class="mb-4">Komentar (<?php echo count($comments); ?>)</h3>

                <?php if (empty($comments)): ?>
                    <p class="text-muted">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                <?php else: ?>
                    <div class="comments-list mb-4">
                        <?php foreach ($comments as $comment): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-title mb-1"><?php echo $comment['name']; ?></h6>
                                        <small class="text-muted"><?php echo time_ago($comment['created_at']); ?></small>
                                    </div>
                                    <p class="card-text"><?php echo nl2br($comment['content']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Comment Form -->
                <div class="card bg-light">
                    <div class="card-header">
                        <h5 class="mb-0">Tulis Komentar</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!is_logged_in()): ?>
                            <div class="alert alert-info">
                                Anda harus <a href="<?php echo BASE_URL; ?>/login">login</a> untuk memberikan komentar.
                            </div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Komentar Anda</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-send"></i> Kirim Komentar
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
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
                    <h5 class="mb-0"><i class="fas fa-star"></i> Artikel Terkait</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($latest as $art): ?>
                        <?php if ($art['id'] !== $article['id']): ?>
                            <a href="<?php echo BASE_URL; ?>/article/<?php echo $art['id']; ?>" class="list-group-item list-group-item-action py-3">
                                <h6 class="mb-1"><?php echo truncate_text($art['title'], 40); ?></h6>
                                <small class="text-muted"><?php echo $art['category_name']; ?></small>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/src/views/layouts/footer.php'; ?>