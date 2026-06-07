<?php
/**
 * Helper Functions
 * Fungsi-fungsi utility untuk portal berita
 */

// Redirect function
function redirect($url = '') {
    header('Location: ' . BASE_URL . '/' . $url);
    exit;
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Get current user
function get_current_user() {
    if (is_logged_in()) {
        $db = Database::getInstance();
        $result = $db->query('SELECT * FROM users WHERE id = ' . $_SESSION['user_id']);
        return $result->fetch_assoc();
    }
    return null;
}

// Sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Generate slug
function generate_slug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^\w\s-]/', '', $text);
    $text = preg_replace('/\s+/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

// Format date
function format_date($date, $format = 'd M Y H:i') {
    return date($format, strtotime($date));
}

// Time ago
function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2419200);
    $years = round($seconds / 29030400);

    if ($seconds <= 60) {
        return 'Baru saja';
    } elseif ($minutes <= 60) {
        return $minutes == 1 ? $minutes . ' menit lalu' : $minutes . ' menit lalu';
    } elseif ($hours <= 24) {
        return $hours == 1 ? $hours . ' jam lalu' : $hours . ' jam lalu';
    } elseif ($days <= 7) {
        return $days == 1 ? $days . ' hari lalu' : $days . ' hari lalu';
    } elseif ($weeks <= 4.3) {
        return $weeks == 1 ? $weeks . ' minggu lalu' : $weeks . ' minggu lalu';
    } elseif ($months <= 12) {
        return $months == 1 ? $months . ' bulan lalu' : $months . ' bulan lalu';
    } else {
        return $years == 1 ? $years . ' tahun lalu' : $years . ' tahun lalu';
    }
}

// Upload file
function upload_file($file, $directory = 'articles') {
    $target_dir = BASE_PATH . '/public/uploads/' . $directory . '/';
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($file['name']);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_type, $allowed)) {
        return false;
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $file_name;
    }

    return false;
}

// Truncate text
function truncate_text($text, $length = 150) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

// Get file URL
function get_image_url($image_name, $directory = 'articles') {
    return BASE_URL . '/public/uploads/' . $directory . '/' . $image_name;
}

// Flash message
function set_flash_message($message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'success';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
?>