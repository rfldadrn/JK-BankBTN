<?php
class User extends Model {
    protected $table = 'users';

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.name as role_name FROM users u 
            JOIN user_roles ur ON u.id = ur.user_id 
            JOIN roles r ON ur.role_id = r.id 
            WHERE u.username = ? AND u.status = 'aktif' LIMIT 1");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.name as role_name FROM users u 
            JOIN user_roles ur ON u.id = ur.user_id 
            JOIN roles r ON ur.role_id = r.id 
            WHERE u.email = ? AND u.status = 'aktif' LIMIT 1");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getAllWithRoles(string $search = '', int $page = 1): array {
        $offset = ($page - 1) * PER_PAGE;
        $where = '';
        $params = [];
        if ($search) {
            $where = "WHERE (u.nama LIKE ? OR u.username LIKE ? OR u.email LIKE ? OR u.nip LIKE ?)";
            $s = "%$search%";
            $params = [$s, $s, $s, $s];
        }
        $sql = "SELECT u.*, r.name as role_name FROM users u 
                JOIN user_roles ur ON u.id = ur.user_id 
                JOIN roles r ON ur.role_id = r.id 
                $where ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
        $params[] = PER_PAGE;
        $params[] = $offset;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(string $search = ''): int {
        $where = '';
        $params = [];
        if ($search) {
            $where = "WHERE (nama LIKE ? OR username LIKE ? OR email LIKE ? OR nip LIKE ?)";
            $s = "%$search%";
            $params = [$s, $s, $s, $s];
        }
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users $where");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getUserWithRole(int $id): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.name as role_name, r.id as role_id FROM users u 
            JOIN user_roles ur ON u.id = ur.user_id 
            JOIN roles r ON ur.role_id = r.id 
            WHERE u.id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function assignRole(int $userId, int $roleId): void {
        $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?")->execute([$userId]);
        $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")->execute([$userId, $roleId]);
    }
}
