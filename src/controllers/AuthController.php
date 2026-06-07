<?php
/**
 * Authentication Controller
 */

require_once BASE_PATH . '/src/models/User.php';

class AuthController {
    private $user_model;

    public function __construct() {
        $this->user_model = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                set_flash_message('Email dan password harus diisi', 'error');
                include BASE_PATH . '/src/views/auth/login.php';
                return;
            }

            $user = $this->user_model->get_by_email($email);

            if ($user && verify_password($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                set_flash_message('Login berhasil!', 'success');
                redirect('admin');
            } else {
                set_flash_message('Email atau password salah', 'error');
            }
        }

        include BASE_PATH . '/src/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
                set_flash_message('Semua field harus diisi', 'error');
                include BASE_PATH . '/src/views/auth/register.php';
                return;
            }

            if (!is_valid_email($email)) {
                set_flash_message('Format email tidak valid', 'error');
                include BASE_PATH . '/src/views/auth/register.php';
                return;
            }

            if ($password !== $confirm_password) {
                set_flash_message('Password tidak cocok', 'error');
                include BASE_PATH . '/src/views/auth/register.php';
                return;
            }

            if (strlen($password) < 6) {
                set_flash_message('Password minimal 6 karakter', 'error');
                include BASE_PATH . '/src/views/auth/register.php';
                return;
            }

            $existing_user = $this->user_model->get_by_email($email);
            if ($existing_user) {
                set_flash_message('Email sudah terdaftar', 'error');
                include BASE_PATH . '/src/views/auth/register.php';
                return;
            }

            $hashed_password = hash_password($password);
            $user_data = [
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password,
                'role' => 'author'
            ];

            if ($this->user_model->create($user_data)) {
                set_flash_message('Registrasi berhasil! Silakan login', 'success');
                redirect('login');
            } else {
                set_flash_message('Registrasi gagal', 'error');
            }
        }

        include BASE_PATH . '/src/views/auth/register.php';
    }
}
?>