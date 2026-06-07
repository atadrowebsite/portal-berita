<?php
/**
 * User Controller
 */

require_once BASE_PATH . '/src/models/User.php';

class UserController {
    private $user_model;

    public function __construct() {
        $this->user_model = new User();
    }

    public function index() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $users = $this->user_model->get_all();
        include BASE_PATH . '/src/views/admin/users/list.php';
    }

    public function create() {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'role' => sanitize($_POST['role'] ?? 'author')
            ];

            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                set_flash_message('Semua field harus diisi', 'error');
            } else if (!is_valid_email($data['email'])) {
                set_flash_message('Format email tidak valid', 'error');
            } else {
                $existing = $this->user_model->get_by_email($data['email']);
                if ($existing) {
                    set_flash_message('Email sudah terdaftar', 'error');
                } else {
                    $data['password'] = hash_password($data['password']);
                    if ($this->user_model->create($data)) {
                        set_flash_message('User berhasil dibuat', 'success');
                        redirect('admin/users');
                    } else {
                        set_flash_message('Gagal membuat user', 'error');
                    }
                }
            }
        }

        include BASE_PATH . '/src/views/admin/users/create.php';
    }

    public function edit($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        $user = $this->user_model->get_by_id($id);
        if (!$user) {
            set_flash_message('User tidak ditemukan', 'error');
            redirect('admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'role' => sanitize($_POST['role'] ?? 'author')
            ];

            if (empty($data['name']) || empty($data['email'])) {
                set_flash_message('Semua field harus diisi', 'error');
            } else if (!is_valid_email($data['email'])) {
                set_flash_message('Format email tidak valid', 'error');
            } else {
                if ($this->user_model->update($id, $data)) {
                    set_flash_message('User berhasil diupdate', 'success');
                    redirect('admin/users');
                } else {
                    set_flash_message('Gagal mengupdate user', 'error');
                }
            }
        }

        include BASE_PATH . '/src/views/admin/users/edit.php';
    }

    public function delete($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            set_flash_message('Akses ditolak', 'error');
            redirect('admin');
        }

        if ($this->user_model->delete($id)) {
            set_flash_message('User berhasil dihapus', 'success');
        } else {
            set_flash_message('Gagal menghapus user', 'error');
        }

        redirect('admin/users');
    }
}
?>