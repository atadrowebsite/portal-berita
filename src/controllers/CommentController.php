<?php
/**
 * Comment Controller
 */

require_once BASE_PATH . '/src/models/Comment.php';

class CommentController {
    private $comment_model;

    public function __construct() {
        $this->comment_model = new Comment();
    }

    public function index() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $comments = $this->comment_model->get_all_admin();
        include BASE_PATH . '/src/views/admin/comments/list.php';
    }

    public function approve($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($this->comment_model->approve($id)) {
            set_flash_message('Komentar berhasil disetujui', 'success');
        } else {
            set_flash_message('Gagal menyetujui komentar', 'error');
        }

        redirect('admin/comments');
    }

    public function reject($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($this->comment_model->reject($id)) {
            set_flash_message('Komentar berhasil ditolak', 'success');
        } else {
            set_flash_message('Gagal menolak komentar', 'error');
        }

        redirect('admin/comments');
    }

    public function delete($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($this->comment_model->delete($id)) {
            set_flash_message('Komentar berhasil dihapus', 'success');
        } else {
            set_flash_message('Gagal menghapus komentar', 'error');
        }

        redirect('admin/comments');
    }
}
?>