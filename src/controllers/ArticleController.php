<?php
/**
 * Article Controller
 */

require_once BASE_PATH . '/src/models/Article.php';
require_once BASE_PATH . '/src/models/Category.php';
require_once BASE_PATH . '/src/models/Comment.php';

class ArticleController {
    private $article_model;
    private $category_model;
    private $comment_model;

    public function __construct() {
        $this->article_model = new Article();
        $this->category_model = new Category();
        $this->comment_model = new Comment();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $articles = $this->article_model->get_all($limit, $offset);
        $categories = $this->category_model->get_all();
        $latest = $this->article_model->get_latest_articles(5);

        include BASE_PATH . '/src/views/frontend/index.php';
    }

    public function detail($id) {
        if (empty($id)) {
            redirect();
        }

        $article = $this->article_model->get_by_id($id);
        if (!$article) {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>Artikel tidak ditemukan</h1>';
            return;
        }

        $comments = $this->comment_model->get_by_article($id);
        $latest = $this->article_model->get_latest_articles(5);
        $categories = $this->category_model->get_all();

        // Handle comment submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
            if (!is_logged_in()) {
                set_flash_message('Anda harus login untuk komentar', 'error');
                redirect('article/' . $id);
            }

            $comment_data = [
                'article_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'content' => sanitize($_POST['comment'])
            ];

            if ($this->comment_model->create($comment_data)) {
                set_flash_message('Komentar Anda akan ditampilkan setelah disetujui admin', 'success');
                redirect('article/' . $id);
            }
        }

        include BASE_PATH . '/src/views/frontend/article-detail.php';
    }

    public function category($slug) {
        if (empty($slug)) {
            redirect();
        }

        $category = $this->category_model->get_by_slug($slug);
        if (!$category) {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>Kategori tidak ditemukan</h1>';
            return;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $articles = $this->article_model->get_by_category($category['id'], $limit, $offset);
        $categories = $this->category_model->get_all();
        $latest = $this->article_model->get_latest_articles(5);

        include BASE_PATH . '/src/views/frontend/category.php';
    }

    public function search() {
        $keyword = sanitize($_GET['q'] ?? '');
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $articles = [];
        if (!empty($keyword)) {
            $articles = $this->article_model->search($keyword, $limit, $offset);
        }

        $categories = $this->category_model->get_all();
        $latest = $this->article_model->get_latest_articles(5);

        include BASE_PATH . '/src/views/frontend/search.php';
    }

    // Admin functions
    public function admin_list() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $articles = $this->article_model->get_admin_articles($limit, $offset);

        include BASE_PATH . '/src/views/admin/articles/list.php';
    }

    public function admin_create() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $categories = $this->category_model->get_all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => sanitize($_POST['title'] ?? ''),
                'content' => $_POST['content'] ?? '',
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'user_id' => $_SESSION['user_id'],
                'status' => sanitize($_POST['status'] ?? 'draft')
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = upload_file($_FILES['image']);
                if ($image) {
                    $data['image'] = $image;
                }
            }

            if (empty($data['title']) || empty($data['content'])) {
                set_flash_message('Judul dan konten harus diisi', 'error');
            } else {
                if ($this->article_model->create($data)) {
                    set_flash_message('Artikel berhasil dibuat', 'success');
                    redirect('admin/articles');
                } else {
                    set_flash_message('Gagal membuat artikel', 'error');
                }
            }
        }

        include BASE_PATH . '/src/views/admin/articles/create.php';
    }

    public function admin_edit($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $article = $this->article_model->get_by_id($id);
        if (!$article) {
            set_flash_message('Artikel tidak ditemukan', 'error');
            redirect('admin/articles');
        }

        $categories = $this->category_model->get_all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => sanitize($_POST['title'] ?? ''),
                'content' => $_POST['content'] ?? '',
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'status' => sanitize($_POST['status'] ?? 'draft')
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = upload_file($_FILES['image']);
                if ($image) {
                    $data['image'] = $image;
                }
            }

            if (empty($data['title']) || empty($data['content'])) {
                set_flash_message('Judul dan konten harus diisi', 'error');
            } else {
                if ($this->article_model->update($id, $data)) {
                    set_flash_message('Artikel berhasil diupdate', 'success');
                    redirect('admin/articles');
                } else {
                    set_flash_message('Gagal mengupdate artikel', 'error');
                }
            }
        }

        include BASE_PATH . '/src/views/admin/articles/edit.php';
    }

    public function admin_delete($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($this->article_model->delete($id)) {
            set_flash_message('Artikel berhasil dihapus', 'success');
        } else {
            set_flash_message('Gagal menghapus artikel', 'error');
        }

        redirect('admin/articles');
    }
}
?>