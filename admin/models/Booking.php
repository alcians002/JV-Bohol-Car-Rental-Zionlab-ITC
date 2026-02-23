<?php
/**
 * Booking Model — Bookings operations
 */

require_once __DIR__ . '/../config/database.php';

class Booking
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Fetch all bookings, joined with vehicle data.
     */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT b.*, v.model_name, v.category, v.image_path ' .
            'FROM bookings b ' .
            'JOIN vehicles v ON b.vehicle_id = v.id ' .
            'ORDER BY b.pickup_date ASC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Fetch upcoming bookings (where pickup date is in the future).
     */
    public function getUpcoming(): array
    {
        $stmt = $this->db->query(
            "SELECT b.*, v.model_name " .
            "FROM bookings b " .
            "JOIN vehicles v ON b.vehicle_id = v.id " .
            "WHERE b.pickup_date > NOW() " .
            "AND b.booking_status IN ('Pending', 'Confirmed') " .
            "ORDER BY b.pickup_date ASC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Update booking status.
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE bookings SET booking_status = :status WHERE id = :id'
        );
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(int $id, string $paymentStatus): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE bookings SET payment_status = :payment_status WHERE id = :id'
        );
        return $stmt->execute([
            ':payment_status' => $paymentStatus,
            ':id' => $id
        ]);
    }

    /**
     * Delete booking by ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM bookings WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Create a new booking.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO bookings (booking_ref, vehicle_id, customer_name, customer_email, customer_phone, pickup_date, return_date, total_price, booking_status, payment_status, notes)
             VALUES (:ref, :vehicle, :name, :email, :phone, :pickup, :return_date, :price, :status, :payment, :notes)'
        );
        return $stmt->execute([
            ':ref' => $data['booking_ref'],
            ':vehicle' => $data['vehicle_id'],
            ':name' => $data['customer_name'],
            ':email' => $data['customer_email'],
            ':phone' => $data['customer_phone'],
            ':pickup' => $data['pickup_date'],
            ':return_date' => $data['return_date'],
            ':price' => $data['total_price'],
            ':status' => $data['booking_status'],
            ':payment' => $data['payment_status'],
            ':notes' => $data['notes'],
        ]);
    }

    /**
     * Search bookings by customer name, ref, or vehicle model
     */
    public function search(string $query): array
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, v.model_name, v.category, v.image_path ' .
            'FROM bookings b ' .
            'JOIN vehicles v ON b.vehicle_id = v.id ' .
            'WHERE b.customer_name LIKE :q OR b.booking_ref LIKE :q OR v.model_name LIKE :q ' .
            'ORDER BY b.pickup_date ASC'
        );
        $stmt->execute([':q' => "%$query%"]);
        return $stmt->fetchAll();
    }
}
