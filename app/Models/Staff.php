<?php
namespace App\Models;

use PDO;

class Staff {
    public function __construct(private PDO $db) {}

    public function all(): array {
        return $this->db->query(
            "SELECT st.*, u.name, u.email, u.phone, u.role
             FROM staff st JOIN users u ON u.id = st.user_id
             ORDER BY u.name"
        )->fetchAll();
    }

    public function paginated(int $page, int $perPage, string $search = '', string $sort = 'id', string $dir = 'asc'): array {
        $allowed = ['id','name','email','specialization','available'];
        $sort    = in_array($sort, $allowed, true) ? $sort : 'id';
        $dir     = $dir === 'asc' ? 'ASC' : 'DESC';
        $offset  = ($page - 1) * $perPage;
        $where   = '';
        $params  = [];

        if ($search) {
            $where = "WHERE u.name LIKE ? OR st.specialization LIKE ? OR u.email LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $total = $this->db->prepare("SELECT COUNT(*) FROM staff st JOIN users u ON u.id=st.user_id $where");
        $total->execute($params);
        $count = (int)$total->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT st.*, u.name, u.email, u.phone, u.role FROM staff st
             JOIN users u ON u.id=st.user_id $where
             ORDER BY u.$sort $dir LIMIT $perPage OFFSET $offset"
        );
        $stmt->execute($params);
        return ['data' => $stmt->fetchAll(), 'total' => $count];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT st.*, u.name, u.email, u.phone, u.password, u.role
             FROM staff st JOIN users u ON u.id=st.user_id WHERE st.id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findAvailable(): array {
        return $this->db->query(
            "SELECT st.id, u.name FROM staff st JOIN users u ON u.id=st.user_id WHERE st.available=1 ORDER BY u.name"
        )->fetchAll();
    }

    public function create(array $d): int {
        $this->db->beginTransaction();
        try {
            $role = in_array($d['role'] ?? '', ['admin', 'staff'], true) ? $d['role'] : 'staff';
            $stmt = $this->db->prepare(
                "INSERT INTO users (name,email,password,phone,role) VALUES (?,?,?,?,?)"
            );
            $stmt->execute([$d['name'], $d['email'], password_hash($d['password'], PASSWORD_DEFAULT), $d['phone'] ?? null, $role]);
            $userId = (int)$this->db->lastInsertId();

            $stmt2 = $this->db->prepare("INSERT INTO staff (user_id,specialization,bio,available) VALUES (?,?,?,?)");
            $stmt2->execute([$userId, $d['specialization'] ?? null, $d['bio'] ?? null, $d['available'] ?? 1]);
            $staffId = (int)$this->db->lastInsertId();

            if (!empty($d['availability'])) {
                $this->saveAvailability($staffId, $d['availability']);
            }
            $this->db->commit();
            return $staffId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $d): void {
        $staff = $this->find($id);
        if (!$staff) return;

        $role = in_array($d['role'] ?? '', ['admin', 'staff'], true) ? $d['role'] : 'staff';
        $stmt = $this->db->prepare("UPDATE users SET name=?,email=?,phone=?,role=? WHERE id=?");
        $stmt->execute([$d['name'], $d['email'], $d['phone'] ?? null, $role, $staff['user_id']]);

        if (!empty($d['password'])) {
            $this->db->prepare("UPDATE users SET password=? WHERE id=?")->execute([password_hash($d['password'], PASSWORD_DEFAULT), $staff['user_id']]);
        }

        $stmt2 = $this->db->prepare("UPDATE staff SET specialization=?,bio=?,available=? WHERE id=?");
        $stmt2->execute([$d['specialization'] ?? null, $d['bio'] ?? null, $d['available'] ?? 1, $id]);

        if (isset($d['availability'])) {
            $this->db->prepare("DELETE FROM staff_availability WHERE staff_id=?")->execute([$id]);
            $this->saveAvailability($id, $d['availability']);
        }
    }

    private function saveAvailability(int $staffId, array $availability): void {
        $stmt = $this->db->prepare("INSERT INTO staff_availability (staff_id,day_of_week,start_time,end_time) VALUES (?,?,?,?)");
        foreach ($availability as $day => $times) {
            if (!empty($times['enabled'])) {
                $stmt->execute([$staffId, (int)$day, $times['start'] ?? '09:00', $times['end'] ?? '18:00']);
            }
        }
    }

    public function delete(int $id): void {
        $staff = $this->find($id);
        if ($staff) {
            $this->db->prepare("DELETE FROM users WHERE id=?")->execute([$staff['user_id']]);
        }
    }

    public function getAvailability(int $staffId): array {
        $stmt = $this->db->prepare("SELECT * FROM staff_availability WHERE staff_id=?");
        $stmt->execute([$staffId]);
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['day_of_week']] = $row;
        }
        return $result;
    }
}
