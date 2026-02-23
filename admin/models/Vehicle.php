<?php
/**
 * Vehicle Model — CRUD Operations (v2)
 * Expanded with category, color, seating, transmission, price_per_day, badge_label.
 * All queries use PDO prepared statements to prevent SQL injection.
 */

require_once __DIR__ . '/../config/database.php';

class Vehicle
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* -------------------------------------------------------
     * READ
     * ----------------------------------------------------- */

    /**
     * Fetch all vehicles, newest first.
     */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM vehicles ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Fetch a single vehicle by ID.
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM vehicles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Search vehicles by model name (partial match).
     */
    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM vehicles WHERE model_name LIKE :kw ORDER BY created_at DESC'
        );
        $stmt->execute([':kw' => '%' . $keyword . '%']);
        return $stmt->fetchAll();
    }

    /**
     * Fetch only Available vehicles (for public-facing pages).
     */
    public function getAvailable(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM vehicles WHERE status = 'Available' ORDER BY
             FIELD(category, 'hatchbacks','sedans','mpvs','pickup-trucks','vans'),
             year ASC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Fetch Available vehicles filtered by category.
     */
    public function getAvailableByCategory(string $category): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM vehicles WHERE status = 'Available' AND category = :cat ORDER BY year ASC"
        );
        $stmt->execute([':cat' => $category]);
        return $stmt->fetchAll();
    }

    /* -------------------------------------------------------
     * CREATE
     * ----------------------------------------------------- */

    /**
     * Insert a new vehicle record.
     * Returns the new row ID on success.
     */
    public function create(
        string $modelName,
        int $year,
        string $category,
        string $color,
        string $seating,
        string $transmission,
        float $pricePerDay,
        ?string $badgeLabel,
        string $status,
        ?string $imagePath = null
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO vehicles
                (model_name, year, category, color, seating, transmission, price_per_day, badge_label, status, image_path)
             VALUES
                (:model, :year, :cat, :color, :seat, :trans, :price, :badge, :status, :img)'
        );
        $stmt->execute([
            ':model' => $modelName,
            ':year' => $year,
            ':cat' => $category,
            ':color' => $color,
            ':seat' => $seating,
            ':trans' => $transmission,
            ':price' => $pricePerDay,
            ':badge' => $badgeLabel ?: null,
            ':status' => $status,
            ':img' => $imagePath,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /* -------------------------------------------------------
     * UPDATE
     * ----------------------------------------------------- */

    /**
     * Update all editable fields for a vehicle.
     */
    public function update(
        int $id,
        string $modelName,
        int $year,
        string $category,
        string $color,
        string $seating,
        string $transmission,
        float $pricePerDay,
        ?string $badgeLabel,
        string $status,
        ?string $imagePath = null
    ): bool {
        $fields = 'model_name = :model, year = :year, category = :cat, color = :color,
                    seating = :seat, transmission = :trans, price_per_day = :price,
                    badge_label = :badge, status = :status';
        $params = [
            ':model' => $modelName,
            ':year' => $year,
            ':cat' => $category,
            ':color' => $color,
            ':seat' => $seating,
            ':trans' => $transmission,
            ':price' => $pricePerDay,
            ':badge' => $badgeLabel ?: null,
            ':status' => $status,
            ':id' => $id,
        ];

        if ($imagePath !== null) {
            $fields .= ', image_path = :img';
            $params[':img'] = $imagePath;
        }

        $stmt = $this->db->prepare("UPDATE vehicles SET {$fields} WHERE id = :id");
        return $stmt->execute($params);
    }

    /**
     * Quick-update status only (used by AJAX).
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE vehicles SET status = :status WHERE id = :id'
        );
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /* -------------------------------------------------------
     * DELETE
     * ----------------------------------------------------- */

    /**
     * Delete a vehicle by ID. Returns the deleted row so the
     * controller can also remove its image file.
     */
    public function delete(int $id): array|false
    {
        $vehicle = $this->getById($id);
        if ($vehicle) {
            $stmt = $this->db->prepare('DELETE FROM vehicles WHERE id = :id');
            $stmt->execute([':id' => $id]);
        }
        return $vehicle;
    }
}
