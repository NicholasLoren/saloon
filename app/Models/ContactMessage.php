<?php
namespace App\Models;

use PDO;

class ContactMessage {
    public function __construct(private PDO $db) {}

    public function all(): array {
        return $this->db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
    }

    public function paginated(int $page, int $perPage, string $search = ''): array {
        $offset = ($page - 1) * $perPage;
        $like   = "%$search%";
        $where  = $search ? "WHERE name LIKE ? OR email LIKE ? OR subject LIKE ?" : "";
        $params = $search ? [$like, $like, $like] : [];

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM contact_messages $where");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $dataStmt = $this->db->prepare("SELECT * FROM contact_messages $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
        $dataStmt->execute($params);
        return ['data' => $dataStmt->fetchAll(), 'total' => $total];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM contact_messages WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM contact_messages WHERE id=?")->execute([$id]);
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare("INSERT INTO contact_messages (name,email,phone,subject,message) VALUES (?,?,?,?,?)");
        $stmt->execute([$d['name'], $d['email'], $d['phone'] ?? null, $d['subject'] ?? null, $d['message']]);
        return (int)$this->db->lastInsertId();
    }

    public function countUnread(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
    }

    public function markRead(int $id): void {
        $this->db->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$id]);
    }
}
