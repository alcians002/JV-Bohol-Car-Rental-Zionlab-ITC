<?php
/**
 * Vehicle Controller — Request Routing & Business Logic (v2)
 * Handles form submissions, image uploads, AJAX endpoints.
 * Expanded to process category, color, seating, transmission, price, badge fields.
 */

require_once __DIR__ . '/../models/Vehicle.php';

class VehicleController
{
    private Vehicle $model;
    private string $uploadDir;

    /** Allowed image MIME types and max file size (2 MB) */
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png'];
    private const MAX_SIZE = 2 * 1024 * 1024; // 2 MB

    private const VALID_CATEGORIES = ['hatchbacks', 'sedans', 'mpvs', 'pickup-trucks', 'vans'];
    private const VALID_STATUSES = ['Available', 'Maintenance', 'Out'];

    public function __construct()
    {
        $this->model = new Vehicle();
        $this->uploadDir = __DIR__ . '/../uploads/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /* -------------------------------------------------------
     * ROUTE DISPATCHER
     * ----------------------------------------------------- */

    public function dispatch(string $action): void
    {
        switch ($action) {
            case 'create':
                $this->handleCreate();
                break;
            case 'update':
                $this->handleUpdate();
                break;
            case 'delete':
                $this->handleDelete();
                break;
            case 'update_status':
                $this->handleUpdateStatus();
                break;
            case 'get':
                $this->handleGet();
                break;
            case 'api_public':
                $this->handleApiPublic();
                break;
            case 'list':
            default:
                $this->handleList();
                break;
        }
    }

    /* -------------------------------------------------------
     * ACTIONS
     * ----------------------------------------------------- */

    /** GET — Render the dashboard. */
    private function handleList(): void
    {
        $search = trim($_GET['search'] ?? '');
        $vehicles = $search !== ''
            ? $this->model->search($search)
            : $this->model->getAll();

        require __DIR__ . '/../views/dashboard.php';
    }

    /** GET (AJAX) — Single vehicle as JSON. */
    private function handleGet(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $vehicle = $this->model->getById($id);

        header('Content-Type: application/json');
        echo json_encode($vehicle ?: ['error' => 'Not found']);
        exit;
    }

    /** GET — Public JSON API: available vehicles. */
    private function handleApiPublic(): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($this->model->getAvailable());
        exit;
    }

    /** POST — Create a new vehicle. */
    private function handleCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Vehicle creation requires POST.');
            return;
        }

        $data = $this->extractPostData();
        $imagePath = $this->processUpload('image');

        if ($data['model_name'] === '') {
            $this->redirect('Model name is required.', 'danger');
            return;
        }

        $this->model->create(
            $data['model_name'],
            $data['year'],
            $data['category'],
            $data['color'],
            $data['seating'],
            $data['transmission'],
            $data['price_per_day'],
            $data['badge_label'],
            $data['status'],
            $imagePath
        );
        $this->redirect('Vehicle added successfully!', 'success');
    }

    /** POST — Update an existing vehicle. */
    private function handleUpdate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('Vehicle update requires POST.');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->extractPostData();
        $imagePath = $this->processUpload('image');

        if ($id === 0 || $data['model_name'] === '') {
            $this->redirect('Invalid vehicle data.', 'danger');
            return;
        }

        // Delete old image if a new one is uploaded
        if ($imagePath !== null) {
            $existing = $this->model->getById($id);
            if ($existing && $existing['image_path']) {
                $oldFile = $this->uploadDir . $existing['image_path'];
                if (file_exists($oldFile))
                    unlink($oldFile);
            }
        }

        $this->model->update(
            $id,
            $data['model_name'],
            $data['year'],
            $data['category'],
            $data['color'],
            $data['seating'],
            $data['transmission'],
            $data['price_per_day'],
            $data['badge_label'],
            $data['status'],
            $imagePath
        );
        $this->redirect('Vehicle updated successfully!', 'success');
    }

    /** POST (AJAX) — Delete a vehicle and its image. */
    private function handleDelete(): void
    {
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        if ($id === 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $deleted = $this->model->delete($id);
        if ($deleted && $deleted['image_path']) {
            $file = $this->uploadDir . $deleted['image_path'];
            if (file_exists($file))
                unlink($file);
        }

        echo json_encode(['success' => (bool) $deleted, 'message' => $deleted ? 'Deleted.' : 'Not found.']);
        exit;
    }

    /** POST (AJAX) — Quick status update. */
    private function handleUpdateStatus(): void
    {
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($id === 0 || !in_array($status, self::VALID_STATUSES, true)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            exit;
        }

        $ok = $this->model->updateStatus($id, $status);
        echo json_encode(['success' => $ok]);
        exit;
    }

    /* -------------------------------------------------------
     * HELPERS
     * ----------------------------------------------------- */

    /**
     * Extract and sanitise all vehicle POST fields.
     */
    private function extractPostData(): array
    {
        $category = $_POST['category'] ?? 'hatchbacks';
        if (!in_array($category, self::VALID_CATEGORIES, true)) {
            $category = 'hatchbacks';
        }

        $transmission = $_POST['transmission'] ?? 'Auto';
        if (!in_array($transmission, ['Auto', 'Manual'], true)) {
            $transmission = 'Auto';
        }

        $status = $_POST['status'] ?? 'Available';
        if (!in_array($status, self::VALID_STATUSES, true)) {
            $status = 'Available';
        }

        $badge = trim($_POST['badge_label'] ?? '');

        return [
            'model_name' => trim($_POST['model_name'] ?? ''),
            'year' => (int) ($_POST['year'] ?? date('Y')),
            'category' => $category,
            'color' => trim($_POST['color'] ?? ''),
            'seating' => trim($_POST['seating'] ?? '5 Seater'),
            'transmission' => $transmission,
            'price_per_day' => abs((float) ($_POST['price_per_day'] ?? 0)),
            'badge_label' => $badge !== '' ? $badge : null,
            'status' => $status,
        ];
    }

    /**
     * Process image upload. Returns filename on success, null if none.
     */
    private function processUpload(string $field): ?string
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK)
            return null;

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_TYPES, true))
            return null;
        if ($file['size'] > self::MAX_SIZE)
            return null;

        $ext = $mime === 'image/png' ? 'png' : 'jpg';
        $filename = uniqid('vehicle_', true) . '.' . $ext;

        move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename);
        return $filename;
    }

    /**
     * Redirect with optional flash message.
     */
    private function redirect(string $message = '', string $type = 'info'): void
    {
        $qs = $message !== ''
            ? '?' . http_build_query(['msg' => $message, 'msg_type' => $type])
            : '';
        header('Location: index.php' . $qs);
        exit;
    }
}
