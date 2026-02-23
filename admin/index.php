<?php
/**
 * Front Controller — Single Entry Point
 * All requests route through here → dispatched to designated controller.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'cookie_samesite' => 'Strict',
    ]);
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/controllers/VehicleController.php';

$page = $_GET['page'] ?? 'fleet';
$action = $_GET['action'] ?? 'list';

switch ($page) {
    case 'bookings':
        require_once __DIR__ . '/controllers/BookingController.php';
        $bookingCtrl = new BookingController();
        $bookingCtrl->dispatch($action);
        break;
    case 'analytics':
        require_once __DIR__ . '/views/analytics.php';
        break;
    case 'settings':
        require_once __DIR__ . '/views/settings.php';
        break;
    case 'fleet':
    default:
        $controller = new VehicleController();
        $controller->dispatch($action);
        break;
}
