<?php
namespace App\Models;

use PDO;

class Appointment {
    public function __construct(private PDO $db) {}

    public function paginated(int $page, int $perPage, string $search = '', string $sort = 'created_at', string $dir = 'desc', string $status = ''): array {
        $allowed = ['id','client_name','client_email','appointment_date','appointment_time','status','created_at'];
        $sort    = in_array($sort, $allowed, true) ? $sort : 'created_at';
        $dir     = $dir === 'asc' ? 'ASC' : 'DESC';
        $offset  = ($page - 1) * $perPage;
        $where   = ['1=1'];
        $params  = [];

        if ($search) {
            $where[] = "(a.client_name LIKE ? OR a.client_email LIKE ? OR a.client_phone LIKE ?)";
            array_push($params, "%$search%", "%$search%", "%$search%");
        }
        if ($status) {
            $where[] = "a.status = ?";
            $params[] = $status;
        }

        $w = implode(' AND ', $where);

        $total = $this->db->prepare(
            "SELECT COUNT(*) FROM appointments a WHERE $w"
        );
        $total->execute($params);
        $count = (int)$total->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT a.*, s.name AS service_name, s.price AS service_price,
                    CONCAT(u.name) AS staff_name
             FROM appointments a
             LEFT JOIN services s ON s.id = a.service_id
             LEFT JOIN staff st ON st.id = a.staff_id
             LEFT JOIN users u ON u.id = st.user_id
             WHERE $w
             ORDER BY a.$sort $dir
             LIMIT $perPage OFFSET $offset"
        );
        $stmt->execute($params);

        return ['data' => $stmt->fetchAll(), 'total' => $count];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT a.*, s.name AS service_name, s.price AS service_price,
                    s.duration_minutes, u.name AS staff_name,
                    p.status AS payment_status, p.amount AS payment_amount,
                    p.invoice_number, p.payment_method
             FROM appointments a
             LEFT JOIN services s ON s.id = a.service_id
             LEFT JOIN staff st ON st.id = a.staff_id
             LEFT JOIN users u ON u.id = st.user_id
             LEFT JOIN payments p ON p.appointment_id = a.id
             WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare(
            "INSERT INTO appointments (client_name,client_email,client_phone,service_id,staff_id,appointment_date,appointment_time,status,notes)
             VALUES (?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $d['client_name'], $d['client_email'], $d['client_phone'] ?? null,
            $d['service_id'], $d['staff_id'] ?? null,
            $d['appointment_date'], $d['appointment_time'],
            $d['status'] ?? 'pending', $d['notes'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void {
        $stmt = $this->db->prepare(
            "UPDATE appointments SET client_name=?,client_email=?,client_phone=?,service_id=?,
             staff_id=?,appointment_date=?,appointment_time=?,status=?,notes=? WHERE id=?"
        );
        $stmt->execute([
            $d['client_name'], $d['client_email'], $d['client_phone'] ?? null,
            $d['service_id'], $d['staff_id'] ?? null,
            $d['appointment_date'], $d['appointment_time'],
            $d['status'] ?? 'pending', $d['notes'] ?? null, $id
        ]);
    }

    public function updateStatus(int $id, string $status): void {
        $this->db->prepare("UPDATE appointments SET status=? WHERE id=?")->execute([$status, $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM appointments WHERE id=?")->execute([$id]);
    }

    public function countToday(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM appointments WHERE appointment_date = CURDATE() AND status != 'cancelled'"
        )->fetchColumn();
    }

    public function countPending(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM appointments WHERE status = 'pending'"
        )->fetchColumn();
    }

    public function recentFive(): array {
        return $this->db->query(
            "SELECT a.*, s.name AS service_name FROM appointments a
             LEFT JOIN services s ON s.id = a.service_id
             ORDER BY a.created_at DESC LIMIT 5"
        )->fetchAll();
    }

    public function monthlyRevenue(): array {
        return $this->db->query(
            "SELECT DATE_FORMAT(a.appointment_date,'%Y-%m') AS month, COALESCE(SUM(p.amount),0) AS revenue
             FROM appointments a
             LEFT JOIN payments p ON p.appointment_id = a.id AND p.status='paid'
             WHERE a.appointment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY month ORDER BY month ASC"
        )->fetchAll();
    }

    public function getSlots(string $date, int $serviceId, ?int $staffId): array {
        $stmt = $this->db->prepare("SELECT duration_minutes FROM services WHERE id=? AND active=1");
        $stmt->execute([$serviceId]);
        $svc = $stmt->fetch();
        if (!$svc) return [];

        $duration = (int)$svc['duration_minutes'];
        $dayOfWeek = (int)date('w', strtotime($date));

        if ($staffId) {
            $avail = $this->db->prepare("SELECT start_time,end_time FROM staff_availability WHERE staff_id=? AND day_of_week=?");
            $avail->execute([$staffId, $dayOfWeek]);
            $availRow = $avail->fetch();
            $startH = $availRow ? $availRow['start_time'] : '09:00:00';
            $endH   = $availRow ? $availRow['end_time']   : '18:00:00';
        } else {
            $startH = '09:00:00';
            $endH   = '18:00:00';
        }

        $allSlots = [];
        $t   = strtotime("$date $startH");
        $end = strtotime("$date $endH") - ($duration * 60);
        while ($t <= $end) {
            $allSlots[] = date('H:i', $t);
            $t += 30 * 60;
        }

        $whereStaff = $staffId ? "AND staff_id = $staffId" : "";
        $booked = $this->db->prepare(
            "SELECT a.appointment_time, s.duration_minutes FROM appointments a
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.appointment_date = ? $whereStaff AND a.status != 'cancelled'"
        );
        $booked->execute([$date]);
        $bookedRows = $booked->fetchAll();

        return array_values(array_filter($allSlots, function($slot) use ($date, $duration, $bookedRows) {
            $sStart = strtotime("$date $slot");
            $sEnd   = $sStart + ($duration * 60);
            foreach ($bookedRows as $row) {
                $bStart = strtotime("$date " . $row['appointment_time']);
                $bEnd   = $bStart + ((int)$row['duration_minutes'] * 60);
                if ($sStart < $bEnd && $sEnd > $bStart) return false;
            }
            return true;
        }));
    }

    public function uniqueClients(int $page, int $perPage, string $search = ''): array {
        $where  = $search ? "WHERE client_email LIKE ? OR client_name LIKE ?" : "";
        $params = $search ? ["%$search%", "%$search%"] : [];

        $total = $this->db->prepare(
            "SELECT COUNT(DISTINCT client_email) FROM appointments $where"
        );
        $total->execute($params);

        $stmt = $this->db->prepare(
            "SELECT client_name, client_email, client_phone,
                    COUNT(*) AS total_appointments,
                    MAX(appointment_date) AS last_visit,
                    MIN(appointment_date) AS first_visit
             FROM appointments $where
             GROUP BY client_email, client_name, client_phone
             ORDER BY last_visit DESC
             LIMIT $perPage OFFSET " . (($page - 1) * $perPage)
        );
        $stmt->execute($params);

        return ['data' => $stmt->fetchAll(), 'total' => (int)$total->fetchColumn()];
    }

    public function clientHistory(string $email): array {
        $stmt = $this->db->prepare(
            "SELECT a.*, s.name AS service_name FROM appointments a
             LEFT JOIN services s ON s.id = a.service_id
             WHERE a.client_email = ? ORDER BY a.appointment_date DESC"
        );
        $stmt->execute([$email]);
        return $stmt->fetchAll();
    }
}
