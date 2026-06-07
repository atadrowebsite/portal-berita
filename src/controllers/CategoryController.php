<?php
/**
 * Category Controller
 */

require_once BASE_PATH . '/src/models/Category.php';

class CategoryController {
    private $category_model;

    public function __construct() {
        $this->category_model = new Category();
    }

    public function index() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $categories = $this->category_model->get_all();
        include BASE_PATH . '/src/views/admin/categories/list.php';
    }

    public function create() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? '')
            ];

            if (empty($data['name'])) {
                set_flash_message('Nama kategori harus diisi', 'error');
            } else {
                if ($this->category_model->create($data)) {
                    set_flash_message('Kategori berhasil dibuat', 'success');
                    redirect('admin/categories');
                } else {
                    set_flash_message('Gagal membuat kategori', 'error');
                }
            }
        }

        include BASE_PATH . '/src/views/admin/categories/create.php';
    }

    public function edit($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $category = $this->category_model->get_by_id($id);
        if (!$category) {
            set_flash_message('Kategori tidak ditemukan', 'error');
            redirect('admin/categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? '')
            ];

            if (empty($data['name'])) {
                set_flash_message('Nama kategori harus diisi', 'error');
            } else {
                if ($this->category_model->update($id, $data)) {
                    set_flash_message('Kategori berhasil diupdate', 'success');
                    redirect('admin/categories');
                } else {
                    set_flash_message('Gagal mengupdate kategori', 'error');
                }
            }
        }

        include BASE_PATH . '/src/views/admin/categories/edit.php';
    }

    public function delete($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($this->category_model->delete($id)) {
            set_flash_message('Kategori berhasil dihapus', 'success');
        } else {
            set_flash_message('Gagal menghapus kategori', 'error');
        }

        redirect('admin/categories');
    }
}
?>