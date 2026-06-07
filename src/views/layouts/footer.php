    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin') === false): ?>
    <!-- Frontend Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-newspaper"></i> Portal Berita Premium</h5>
                    <p class="text-muted">Platform berita terpercaya dengan informasi terkini dan akurat dari seluruh dunia.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Kategori Populer</h5>
                    <ul class="list-unstyled">
                        <?php 
                        $categories = (new Category())->get_all();
                        foreach (array_slice($categories, 0, 5) as $cat): 
                        ?>
                            <li><a href="<?php echo BASE_URL; ?>/category/<?php echo $cat['slug']; ?>" class="text-decoration-none text-muted"><?php echo $cat['name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Ikuti Kami</h5>
                    <div class="social-links">
                        <a href="#" class="text-decoration-none text-muted me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-decoration-none text-muted me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-decoration-none text-muted me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-decoration-none text-muted"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <p>&copy; 2024 Portal Berita Premium. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo BASE_URL; ?>/search" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Masukkan kata kunci..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/main.js"></script>
</body>
</html>