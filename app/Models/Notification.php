<?php
namespace App\Models;

use PDO;

class Notification {
    public function __construct(private PDO $db) {}

    public function paginated(int $page, int $perPage): array {
        $offset = ($page - 1) * $perPage;
        $total  = (int)$this->db->query("SELECT COUNT(*) FROM notifications")->fetchColumn();
        $stmt   = $this->db->prepare(
            "SELECT * FROM notifications ORDER BY created_at DESC LIMIT $perPage OFFSET $offset"
        );
        $stmt->execute();
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $type, string $title, string $body = '', string $url = ''): int {
        $stmt = $this->db->prepare(
            "INSERT INTO notifications (type,title,body,url) VALUES (?,?,?,?)"
        );
        $stmt->execute([$type, $title, $body, $url]);
        return (int)$this->db->lastInsertId();
    }

    public function markRead(int $id): void {
        $this->db->prepare("UPDATE notifications SET is_read=1 WHERE id=?")->execute([$id]);
    }

    public function markAllRead(): void {
        $this->db->exec("UPDATE notifications SET is_read=1 WHERE is_read=0");
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM notifications WHERE id=?")->execute([$id]);
    }

    public function deleteAll(): void {
        $this->db->exec("DELETE FROM notifications");
    }

    public function deleteRead(): void {
        $this->db->exec("DELETE FROM notifications WHERE is_read=1");
    }

    public function unreadCount(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM notifications WHERE is_read=0"
        )->fetchColumn();
    }
}
