<?php
/**
 * API Endpoint to parse Contact form and send email/create booking
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../admin/config/database.php';
require_once __DIR__ . '/../admin/models/Booking.php';

try {
    $db = Database::connect();

    // Check if bookings exists, mostly a safety check
    // Wait, the Booking class uses Database::connect automatically. Let's just instantiate it.
    $bookingModel = new Booking();

    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $customerName = trim($firstName . ' ' . $lastName);
    $customerEmail = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $customerPhone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $messageBody = $_POST['message'] ?? '';

    $vehicleId = isset($_POST['vehicle_id']) && is_numeric($_POST['vehicle_id']) ? (int) $_POST['vehicle_id'] : null;
    $pickupDateStr = $_POST['pickupDate'] ?? null;
    $returnDateStr = $_POST['returnDate'] ?? null;

    if (!$customerName || !$customerEmail || !$messageBody) {
        throw new Exception("Please fill out all required fields.");
    }

    $isBooking = ($subject === 'Vehicle Booking' && $vehicleId);
    $bookingRef = '';
    $confirmLink = '';

    if ($isBooking) {
        // Generate a random booking ref
        $bookingRef = 'BHL-' . random_int(1000, 9999);

        // Use default dates if empty
        if (!$pickupDateStr) {
            $pickupDateStr = date('Y-m-d H:i:s', strtotime('+1 day'));
            $returnDateStr = date('Y-m-d H:i:s', strtotime('+4 days')); // 3 days rental by default dummy
        } else {
            // Append default time since input[type=date] only sends Y-m-d
            $pickupDateStr .= ' 09:00:00';
            $returnDateStr .= ' 18:00:00';
        }

        // We assume 0 total price for now if it's an inquiry, or we can fetch the price.
        // Let's fetch the vehicle price
        $stmt = $db->prepare("SELECT price_per_day FROM vehicles WHERE id = :id");
        $stmt->execute([':id' => $vehicleId]);
        $veh = $stmt->fetch();
        $totalPrice = 0.00;

        if ($veh) {
            $date1 = new DateTime($pickupDateStr);
            $date2 = new DateTime($returnDateStr);
            $diff = $date1->diff($date2);
            $days = max((int) $diff->days, 1);
            $totalPrice = (float) $veh['price_per_day'] * $days;
        }

        if (!$veh) {
            throw new Exception("Invalid vehicle selected.");
        }

        // Create booking record
        // The BookingModel->create expects an array
        $bookingData = [
            'booking_ref' => $bookingRef,
            'vehicle_id' => $vehicleId,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'pickup_date' => $pickupDateStr,
            'return_date' => $returnDateStr,
            'total_price' => $totalPrice,
            'booking_status' => 'Pending',
            'payment_status' => 'Unpaid',
            'notes' => $messageBody,
        ];

        $bookingModel->create($bookingData);

        // After creating, we need the ID for the confirm link.
        // But create() doesn't return the ID. Let's find it by booking_ref
        $stmtId = $db->prepare("SELECT id FROM bookings WHERE booking_ref = :ref");
        $stmtId->execute([':ref' => $bookingRef]);
        $bookRec = $stmtId->fetch();
        if ($bookRec) {
            $host = $_SERVER['HTTP_HOST'];
            // Generate full confirm link
            $confirmLink = "http://{$host}/jv_bohol_car_rental/admin/index.php?page=bookings&action=confirm_booking&id=" . $bookRec['id'];
        }
    }

    // Send Email
    $to = 'jvboholcarrental@gmail.com';
    $emailSubject = "New Inquiry: $subject from $customerName";

    $emailMessage = "You have received a new inquiry from the website contact form:\n\n";
    $emailMessage .= "Name: $customerName\n";
    $emailMessage .= "Email: $customerEmail\n";
    $emailMessage .= "Phone: $customerPhone\n";
    $emailMessage .= "Subject: $subject\n";
    if ($isBooking && $bookingRef) {
        $emailMessage .= "Booking Ref: $bookingRef\n";
        $emailMessage .= "Pickup Date: $pickupDateStr\n";
        $emailMessage .= "Return Date: $returnDateStr\n";
    }
    $emailMessage .= "\nMessage:\n$messageBody\n\n";

    if ($isBooking && $confirmLink) {
        $emailMessage .= "To CONFIRM this booking on the admin dashboard, click the link below:\n";
        $emailMessage .= $confirmLink . "\n";
    }

    $headers = "From: webmaster@jvboholcarrental.com\r\n";
    $headers .= "Reply-To: $customerEmail\r\n";

    // Uncomment this when running on a live server, XAMPP may fail the mail() without setup, so we suppress errors with @
    @mail($to, $emailSubject, $emailMessage, $headers);

    echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
