<?php
namespace App\Models;

use PDO;

class Inventory {
    public function __construct(private PDO $db) {}

    public function paginated(int $page, int $perPage, string $search = '', string $sort = 'product_name', string $dir = 'asc'): array {
        $allowed = ['id','product_name','category','quantity','reorder_level','cost_price','last_updated'];
        $sort    = in_array($sort, $allowed, true) ? $sort : 'product_name';
        $dir     = $dir === 'asc' ? 'ASC' : 'DESC';
        $offset  = ($page - 1) * $perPage;
        $where   = '';
        $params  = [];

        if ($search) {
            $where  = "WHERE product_name LIKE ? OR category LIKE ? OR supplier LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $total = $this->db->prepare("SELECT COUNT(*) FROM inventory $where");
        $total->execute($params);
        $count = (int)$total->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM inventory $where ORDER BY $sort $dir LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        return ['data' => $stmt->fetchAll(), 'total' => $count];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare(
            "INSERT INTO inventory (product_name,category,quantity,unit,reorder_level,cost_price,supplier) VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->execute([$d['product_name'], $d['category'] ?? null, $d['quantity'], $d['unit'] ?? 'pcs', $d['reorder_level'], $d['cost_price'] ?? 0, $d['supplier'] ?? null]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void {
        $stmt = $this->db->prepare(
            "UPDATE inventory SET product_name=?,category=?,quantity=?,unit=?,reorder_level=?,cost_price=?,supplier=? WHERE id=?"
        );
        $stmt->execute([$d['product_name'], $d['category'] ?? null, $d['quantity'], $d['unit'] ?? 'pcs', $d['reorder_level'], $d['cost_price'] ?? 0, $d['supplier'] ?? null, $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM inventory WHERE id=?")->execute([$id]);
    }

    public function lowStockCount(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM inventory WHERE quantity <= reorder_level")->fetchColumn();
    }

    public function categories(): array {
        return $this->db->query("SELECT DISTINCT category FROM inventory ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    }
}
