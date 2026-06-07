<?php
/**
 * Portal Berita - Entry Point
 * Aplikasi portal berita berbasis PHP dengan sistem admin dashboard
 */

session_start();

// Define base path
define('BASE_PATH', __DIR__);
define('BASE_URL', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST']);

// Require configuration and helpers
require_once BASE_PATH . '/src/config/Database.php';
require_once BASE_PATH . '/src/helpers/functions.php';

// Get the URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url_parts = explode('/', $url);

// Router logic
if (empty($url)) {
    // Homepage
    require_once BASE_PATH . '/src/controllers/ArticleController.php';
    $controller = new ArticleController();
    $controller->index();
} elseif ($url_parts[0] === 'admin') {
    // Admin routes
    if (!isset($_SESSION['user_id'])) {
        redirect('login');
    }
    
    if (!isset($url_parts[1])) {
        require_once BASE_PATH . '/src/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
    } elseif ($url_parts[1] === 'articles') {
        require_once BASE_PATH . '/src/controllers/ArticleController.php';
        $controller = new ArticleController();
        $action = isset($url_parts[2]) ? $url_parts[2] : 'admin_list';
        $id = isset($url_parts[3]) ? $url_parts[3] : null;
        $controller->$action($id);
    } elseif ($url_parts[1] === 'categories') {
        require_once BASE_PATH . '/src/controllers/CategoryController.php';
        $controller = new CategoryController();
        $action = isset($url_parts[2]) ? $url_parts[2] : 'index';
        $id = isset($url_parts[3]) ? $url_parts[3] : null;
        $controller->$action($id);
    } elseif ($url_parts[1] === 'users') {
        require_once BASE_PATH . '/src/controllers/UserController.php';
        $controller = new UserController();
        $action = isset($url_parts[2]) ? $url_parts[2] : 'index';
        $id = isset($url_parts[3]) ? $url_parts[3] : null;
        $controller->$action($id);
    } elseif ($url_parts[1] === 'comments') {
        require_once BASE_PATH . '/src/controllers/CommentController.php';
        $controller = new CommentController();
        $action = isset($url_parts[2]) ? $url_parts[2] : 'index';
        $id = isset($url_parts[3]) ? $url_parts[3] : null;
        $controller->$action($id);
    } elseif ($url_parts[1] === 'logout') {
        session_destroy();
        redirect('');
    }
} elseif ($url_parts[0] === 'login') {
    require_once BASE_PATH . '/src/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->login();
} elseif ($url_parts[0] === 'register') {
    require_once BASE_PATH . '/src/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->register();
} elseif ($url_parts[0] === 'article') {
    require_once BASE_PATH . '/src/controllers/ArticleController.php';
    $controller = new ArticleController();
    $id = isset($url_parts[1]) ? $url_parts[1] : null;
    $controller->detail($id);
} elseif ($url_parts[0] === 'category') {
    require_once BASE_PATH . '/src/controllers/ArticleController.php';
    $controller = new ArticleController();
    $slug = isset($url_parts[1]) ? $url_parts[1] : null;
    $controller->category($slug);
} elseif ($url_parts[0] === 'search') {
    require_once BASE_PATH . '/src/controllers/ArticleController.php';
    $controller = new ArticleController();
    $controller->search();
} else {
    // 404 Not Found
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404 - Halaman tidak ditemukan</h1>';
}
?>