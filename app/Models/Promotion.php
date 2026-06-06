<?php
namespace App\Models;

use PDO;

class Promotion {
    public function __construct(private PDO $db) {}

    public function allActive(): array {
        return $this->db->query(
            "SELECT * FROM promotions WHERE active=1 AND (end_date IS NULL OR end_date >= CURDATE()) ORDER BY created_at DESC"
        )->fetchAll();
    }

    public function all(): array {
        return $this->db->query("SELECT * FROM promotions ORDER BY created_at DESC")->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM promotions WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare(
            "INSERT INTO promotions (title,description,discount_percent,start_date,end_date,active) VALUES (?,?,?,?,?,?)"
        );
        $stmt->execute([
            $d['title'], $d['description'] ?? null,
            $d['discount_percent'] ?? 0,
            $d['start_date'] ?: null, $d['end_date'] ?: null,
            $d['active'] ?? 1,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void {
        $stmt = $this->db->prepare(
            "UPDATE promotions SET title=?,description=?,discount_percent=?,start_date=?,end_date=?,active=? WHERE id=?"
        );
        $stmt->execute([
            $d['title'], $d['description'] ?? null,
            $d['discount_percent'] ?? 0,
            $d['start_date'] ?: null, $d['end_date'] ?: null,
            $d['active'] ?? 1, $id,
        ]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM promotions WHERE id=?")->execute([$id]);
    }
}
