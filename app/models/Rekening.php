<?php
class Rekening extends Model {
    protected $table = 'rekening';

    public function getAll(array $filter = [], int $page = 1): array {
        $offset = ($page - 1) * PER_PAGE;
        $where = ['1=1'];
        $params = [];

        if (!empty($filter['search'])) {
            $s = '%' . $filter['search'] . '%';
            $where[] = '(r.no_rekening LIKE ? OR n.nama_lengkap LIKE ? OR n.no_nasabah LIKE ?)';
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filter['status'])) {
            $where[] = 'r.status = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['jenis_rekening'])) {
            $where[] = 'r.jenis_rekening = ?';
            $params[] = $filter['jenis_rekening'];
        }
        if (!empty($filter['nasabah_id'])) {
            $where[] = 'r.nasabah_id = ?';
            $params[] = $filter['nasabah_id'];
        }

        $whereStr = implode(' AND ', $where);
        $sql = "SELECT r.*, n.nama_lengkap, n.no_nasabah 
                FROM rekening r 
                JOIN nasabah n ON r.nasabah_id = n.id 
                WHERE $whereStr 
                ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $params[] = PER_PAGE;
        $params[] = $offset;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered(array $filter = []): int {
        $where = ['1=1'];
        $params = [];
        if (!empty($filter['search'])) {
            $s = '%' . $filter['search'] . '%';
            $where[] = '(r.no_rekening LIKE ? OR n.nama_lengkap LIKE ? OR n.no_nasabah LIKE ?)';
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filter['status'])) { $where[] = 'r.status = ?'; $params[] = $filter['status']; }
        if (!empty($filter['jenis_rekening'])) { $where[] = 'r.jenis_rekening = ?'; $params[] = $filter['jenis_rekening']; }
        if (!empty($filter['nasabah_id'])) { $where[] = 'r.nasabah_id = ?'; $params[] = $filter['nasabah_id']; }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rekening r JOIN nasabah n ON r.nasabah_id = n.id WHERE $whereStr");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getByNasabah(int $nasabahId): array {
        $stmt = $this->db->prepare("SELECT * FROM rekening WHERE nasabah_id = ? ORDER BY created_at DESC");
        $stmt->execute([$nasabahId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetail(int $id): ?array {
        $stmt = $this->db->prepare("SELECT r.*, n.nama_lengkap, n.no_nasabah FROM rekening r JOIN nasabah n ON r.nasabah_id = n.id WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
