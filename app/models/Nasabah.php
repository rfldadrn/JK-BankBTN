<?php
class Nasabah extends Model {
    protected $table = 'nasabah';

    public function getAll(array $filter = [], int $page = 1): array {
        $offset = ($page - 1) * PER_PAGE;
        $where = ['n.deleted_at IS NULL'];
        $params = [];

        if (!empty($filter['search'])) {
            $s = '%' . $filter['search'] . '%';
            $where[] = '(n.nama_lengkap LIKE ? OR n.nik LIKE ? OR n.no_nasabah LIKE ? OR n.no_telepon LIKE ?)';
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($filter['status'])) {
            $where[] = 'n.status = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['jenis_kelamin'])) {
            $where[] = 'n.jenis_kelamin = ?';
            $params[] = $filter['jenis_kelamin'];
        }

        $whereStr = implode(' AND ', $where);
        $sql = "SELECT n.* FROM nasabah n WHERE $whereStr ORDER BY n.created_at DESC LIMIT ? OFFSET ?";
        $params[] = PER_PAGE;
        $params[] = $offset;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered(array $filter = []): int {
        $where = ['deleted_at IS NULL'];
        $params = [];
        if (!empty($filter['search'])) {
            $s = '%' . $filter['search'] . '%';
            $where[] = '(nama_lengkap LIKE ? OR nik LIKE ? OR no_nasabah LIKE ? OR no_telepon LIKE ?)';
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($filter['status'])) {
            $where[] = 'status = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['jenis_kelamin'])) {
            $where[] = 'jenis_kelamin = ?';
            $params[] = $filter['jenis_kelamin'];
        }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM nasabah WHERE $whereStr");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getDetail(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM nasabah WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function softDelete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE nasabah SET status = 'nonaktif', deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function checkDuplicate(string $nik, ?int $exceptId = null): bool {
        $sql = "SELECT COUNT(*) FROM nasabah WHERE nik = ? AND deleted_at IS NULL";
        $params = [$nik];
        if ($exceptId) { $sql .= " AND id != ?"; $params[] = $exceptId; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }
}
