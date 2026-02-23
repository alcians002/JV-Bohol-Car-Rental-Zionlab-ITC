<?php
/**
 * Public Bootstrap — Shared database access for public-facing pages.
 *
 * Usage: require_once __DIR__ . '/../public_bootstrap.php'; (from a page subfolder)
 *
 * Provides:
 *   $vehicles — array of available vehicles from the database
 *   UPLOAD_URL — relative path to the admin uploads directory
 */

// Resolve paths relative to this file (project root)
require_once __DIR__ . '/admin/config/database.php';
require_once __DIR__ . '/admin/models/Vehicle.php';

// Fetch all available vehicles for public display
$vehicleModel = new Vehicle();
$vehicles = $vehicleModel->getAvailable();

/**
 * Relative URL from any page subfolder to the upload directory.
 * Usage in <img>: src="<?= UPLOAD_URL . $v['image_path'] ?>"
 */
define('UPLOAD_URL', '../admin/uploads/');

/**
 * Helper: get a category-specific CSS color class prefix based on page context.
 * Override this constant in individual pages before including templates if needed.
 */
if (!defined('COLOR_PROFILE')) {
    define('COLOR_PROFILE', 'homepage');
}
