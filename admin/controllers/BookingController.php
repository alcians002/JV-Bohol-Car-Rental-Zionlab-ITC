<?php
/**
 * Booking Controller — Handles Bookings Data
 */

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Vehicle.php';

class BookingController
{
    private Booking $model;

    public function __construct()
    {
        $this->model = new Booking();
    }

    public function dispatch(string $action): void
    {
        switch ($action) {
            case 'update_status':
                $this->handleUpdateStatus();
                break;
            case 'update_payment':
                $this->handleUpdatePayment();
                break;
            case 'confirm_booking':
                $this->handleConfirmBooking();
                break;
            case 'create':
                $this->handleCreate();
                break;
            case 'export':
                $this->handleExport();
                break;
            case 'delete':
                $this->handleDelete();
                break;
            case 'list':
            default:
                $this->handleList();
                break;
        }
    }

    private function handleList(): void
    {
        $searchQuery = $_GET['q'] ?? '';

        if (!empty($searchQuery)) {
            $bookings = $this->model->search($searchQuery);
        } else {
            $bookings = $this->model->getAll();
        }

        $allBookings = $this->model->getAll(); // For stats regardless of search
        $pendingCount = count(array_filter($allBookings, fn($b) => $b['booking_status'] === 'Pending'));
        $activeCount = count(array_filter($allBookings, fn($b) => $b['booking_status'] === 'Active'));

        // Get vehicles for the Add Booking modal
        $vehicleModel = new Vehicle();
        $vehicles = $vehicleModel->getAll();

        // Determine if there's a status message
        $msg = $_GET['msg'] ?? '';
        $msgType = $_GET['msg_type'] ?? 'info';

        require_once __DIR__ . '/../views/bookings.php';
    }

    private function handleUpdateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['error' => 'Use POST method']);
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        $validStatuses = ['Pending', 'Confirmed', 'Active', 'Completed', 'Cancelled'];

        if ($id <= 0 || !in_array($status, $validStatuses, true)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid ID or status value']);
            return;
        }

        if ($this->model->updateStatus($id, $status)) {
            echo json_encode(['success' => true]);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Database update failed']);
        }
        exit;
    }

    private function handleUpdatePayment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['error' => 'Use POST method']);
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $paymentStatus = $_POST['payment_status'] ?? '';

        $validStatuses = ['Unpaid', 'Deposit Paid', 'Fully Paid'];

        if ($id <= 0 || !in_array($paymentStatus, $validStatuses, true)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid ID or payment status value']);
            return;
        }

        if ($this->model->updatePaymentStatus($id, $paymentStatus)) {
            echo json_encode(['success' => true]);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Database update failed']);
        }
        exit;
    }

    private function handleConfirmBooking(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('invalid_id', 'danger');
            return;
        }

        if ($this->model->updateStatus($id, 'Confirmed')) {
            $this->redirect('booking_confirmed', 'success');
        } else {
            $this->redirect('error', 'danger');
        }
    }

    private function handleDelete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('invalid_id', 'danger');
        }

        if ($this->model->delete($id)) {
            $this->redirect('deleted', 'success');
        } else {
            $this->redirect('error', 'danger');
        }
    }

    private function handleCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('error', 'danger');
        }

        $data = [
            'booking_ref' => 'BHL-' . strtoupper(substr(uniqid(), -5)), // Generate pseudo-random ref
            'vehicle_id' => (int) ($_POST['vehicle_id'] ?? 0),
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'customer_email' => trim($_POST['customer_email'] ?? ''),
            'customer_phone' => trim($_POST['customer_phone'] ?? ''),
            'pickup_date' => trim($_POST['pickup_date'] ?? ''),
            'return_date' => trim($_POST['return_date'] ?? ''),
            'total_price' => (float) ($_POST['total_price'] ?? 0),
            'booking_status' => trim($_POST['booking_status'] ?? 'Pending'),
            'payment_status' => trim($_POST['payment_status'] ?? 'Unpaid'),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        if (empty($data['customer_name']) || empty($data['pickup_date']) || empty($data['return_date']) || $data['vehicle_id'] === 0) {
            $this->redirect('invalid_data', 'danger');
        }

        if ($this->model->create($data)) {
            $this->redirect('created', 'success');
        } else {
            $this->redirect('error', 'danger');
        }
    }

    private function handleExport(): void
    {
        $bookings = $this->model->getAll();

        $filename = "jvbohol_bookings_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Booking Ref', 'Customer Name', 'Customer Email', 'Customer Phone', 'Vehicle', 'Pickup Date', 'Return Date', 'Total Price', 'Status', 'Payment']);

        foreach ($bookings as $b) {
            fputcsv($output, [
                $b['booking_ref'],
                $b['customer_name'],
                $b['customer_email'],
                $b['customer_phone'],
                $b['model_name'],
                $b['pickup_date'],
                $b['return_date'],
                $b['total_price'],
                $b['booking_status'],
                $b['payment_status']
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Redirect helper.
     */
    private function redirect(string $msgKey, string $type): void
    {
        $messages = [
            'invalid_id' => 'Invalid booking ID.',
            'invalid_data' => 'Please fill all required fields.',
            'deleted' => 'Booking deleted successfully.',
            'created' => 'Booking manually created.',
            'booking_confirmed' => 'Booking status successfully updated to Confirmed.',
            'error' => 'A database error occurred.'
        ];

        $message = urlencode($messages[$msgKey] ?? 'Unknown error.');
        header("Location: index.php?page=bookings&msg={$message}&msg_type={$type}");
        exit;
    }
}
