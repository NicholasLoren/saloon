<?php
namespace App\Models;

use PDO;

class Payment {
    public function __construct(private PDO $db) {}

    public function paginated(int $page, int $perPage, string $search = '', string $sort = 'created_at', string $dir = 'desc', string $status = ''): array {
        $allowed = ['id','invoice_number','amount','payment_method','status','created_at'];
        $sort    = in_array($sort, $allowed, true) ? $sort : 'created_at';
        $dir     = $dir === 'asc' ? 'ASC' : 'DESC';
        $offset  = ($page - 1) * $perPage;
        $where   = ['1=1'];
        $params  = [];

        if ($search) {
            $where[] = "(p.invoice_number LIKE ? OR a.client_name LIKE ? OR a.client_email LIKE ?)";
            array_push($params, "%$search%", "%$search%", "%$search%");
        }
        if ($status) { $where[] = "p.status=?"; $params[] = $status; }

        $w = implode(' AND ', $where);
        $total = $this->db->prepare("SELECT COUNT(*) FROM payments p LEFT JOIN appointments a ON a.id=p.appointment_id WHERE $w");
        $total->execute($params);
        $count = (int)$total->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT p.*, a.client_name, a.client_email, s.name AS service_name
             FROM payments p
             LEFT JOIN appointments a ON a.id=p.appointment_id
             LEFT JOIN services s ON s.id=a.service_id
             WHERE $w ORDER BY p.$sort $dir LIMIT $perPage OFFSET $offset"
        );
        $stmt->execute($params);
        return ['data' => $stmt->fetchAll(), 'total' => $count];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, a.client_name, a.client_email, a.client_phone, a.appointment_date, a.appointment_time,
                    a.notes, s.name AS service_name, s.price AS service_price
             FROM payments p
             LEFT JOIN appointments a ON a.id=p.appointment_id
             LEFT JOIN services s ON s.id=a.service_id
             WHERE p.id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByAppointment(int $appointmentId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE appointment_id=?");
        $stmt->execute([$appointmentId]);
        return $stmt->fetch() ?: null;
    }

    public function createForAppointment(int $appointmentId, float $amount): int {
        $stmt = $this->db->prepare(
            "INSERT INTO payments (appointment_id,amount,invoice_number) VALUES (?,?,?)"
        );
        $stmt->execute([$appointmentId, $amount, generateInvoiceNumber()]);
        return (int)$this->db->lastInsertId();
    }

    public function markPaid(int $id, string $method): void {
        $this->db->prepare(
            "UPDATE payments SET status='paid', payment_method=?, paid_at=NOW() WHERE id=?"
        )->execute([$method, $id]);
    }

    public function totalRevenue(): float {
        return (float)$this->db->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'")->fetchColumn();
    }

    public function revenueThisMonth(): float {
        return (float)$this->db->query(
            "SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid' AND MONTH(paid_at)=MONTH(CURDATE()) AND YEAR(paid_at)=YEAR(CURDATE())"
        )->fetchColumn();
    }
}
