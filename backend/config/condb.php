<?php
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbName = getenv('DB_NAME') ?: "pos_system";

$conn = new mysqli($host, $user, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Global helper for HTML escaping to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF token field helper
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * CSRF token verification helper
 */
function csrf_verify() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token validation failed. Possible cross-site request forgery.");
        }
    }
}

/**
 * Redirect with SweetAlert notification
 */
function redirect_with_swal($icon, $title, $text, $url) {
    echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body>";
    echo "<script>
        Swal.fire({ icon: '$icon', title: '$title', text: '$text' })
        .then(() => { window.location = '$url'; });
    </script></body></html>";
    exit();
}
