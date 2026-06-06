<?php
namespace App\Models;

use PDO;

class Service {
    public function __construct(private PDO $db) {}

    public function all(bool $activeOnly = false): array {
        $sql = "SELECT * FROM services" . ($activeOnly ? " WHERE active = 1" : "") . " ORDER BY category, name";
        return $this->db->query($sql)->fetchAll();
    }

    public function paginated(int $page, int $perPage, string $search = '', string $sort = 'id', string $dir = 'desc'): array {
        $allowed = ['id','name','category','price','duration_minutes','active'];
        $sort    = in_array($sort, $allowed, true) ? $sort : 'id';
        $dir     = $dir === 'asc' ? 'ASC' : 'DESC';
        $offset  = ($page - 1) * $perPage;
        $where   = '';
        $params  = [];

        if ($search) {
            $where = "WHERE name LIKE ? OR category LIKE ? OR description LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $total = $this->db->prepare("SELECT COUNT(*) FROM services $where");
        $total->execute($params);
        $count = (int)$total->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM services $where ORDER BY $sort $dir LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);

        return ['data' => $stmt->fetchAll(), 'total' => $count];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function categories(): array {
        return $this->db->query("SELECT DISTINCT category FROM services ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare(
            "INSERT INTO services (name,description,price,duration_minutes,category,image,active)
             VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->execute([$d['name'], $d['description'] ?? null, $d['price'], $d['duration_minutes'], $d['category'], $d['image'] ?? null, $d['active'] ?? 1]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void {
        $stmt = $this->db->prepare(
            "UPDATE services SET name=?,description=?,price=?,duration_minutes=?,category=?,active=?" .
            (isset($d['image']) ? ",image=?" : "") . " WHERE id=?"
        );
        $params = [$d['name'], $d['description'] ?? null, $d['price'], $d['duration_minutes'], $d['category'], $d['active'] ?? 1];
        if (isset($d['image'])) $params[] = $d['image'];
        $params[] = $id;
        $stmt->execute($params);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM services WHERE active=1")->fetchColumn();
    }
}
