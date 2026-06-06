<?php
namespace App\Models;

use PDO;

class FAQ {
    public function __construct(private PDO $db) {}

    public function all(bool $activeOnly = true): array {
        $sql = "SELECT * FROM faqs" . ($activeOnly ? " WHERE active=1" : "") . " ORDER BY sort_order, id";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM faqs WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare("INSERT INTO faqs (question,answer,category,active,sort_order) VALUES (?,?,?,?,?)");
        $stmt->execute([$d['question'], $d['answer'], $d['category'] ?? 'General', $d['active'] ?? 1, $d['sort_order'] ?? 0]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void {
        $stmt = $this->db->prepare("UPDATE faqs SET question=?,answer=?,category=?,active=?,sort_order=? WHERE id=?");
        $stmt->execute([$d['question'], $d['answer'], $d['category'] ?? 'General', $d['active'] ?? 1, $d['sort_order'] ?? 0, $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM faqs WHERE id=?")->execute([$id]);
    }
}
