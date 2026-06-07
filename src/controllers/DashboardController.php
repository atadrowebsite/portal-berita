<?php
/**
 * Dashboard Controller
 */

require_once BASE_PATH . '/src/models/Article.php';
require_once BASE_PATH . '/src/models/User.php';
require_once BASE_PATH . '/src/models/Category.php';
require_once BASE_PATH . '/src/models/Comment.php';

class DashboardController {
    private $article_model;
    private $user_model;
    private $category_model;
    private $comment_model;

    public function __construct() {
        $this->article_model = new Article();
        $this->user_model = new User();
        $this->category_model = new Category();
        $this->comment_model = new Comment();
    }

    public function index() {
        $total_articles = $this->article_model->get_total_articles();
        $total_users = $this->user_model->get_user_count();
        $total_categories = $this->category_model->get_category_count();
        $latest_articles = $this->article_model->get_latest_articles(5);

        include BASE_PATH . '/src/views/admin/dashboard.php';
    }
}
?>