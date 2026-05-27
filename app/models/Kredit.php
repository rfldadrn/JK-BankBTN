<?php
class Kredit extends Model {
    protected $table = 'kredit';

    public function getAll(array $filter = [], int $page = 1): array {
        $offset = ($page - 1) * PER_PAGE;
        $where = ['1=1'];
        $params = [];

        if (!empty($filter['search'])) {
            $s = '%' . $filter['search'] . '%';
            $where[] = '(k.no_kredit LIKE ? OR n.nama_lengkap LIKE ? OR k.jenis_kredit LIKE ?)';
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filter['status'])) { $where[] = 'k.status = ?'; $params[] = $filter['status']; }
        if (!empty($filter['kolektibilitas'])) { $where[] = 'k.kolektibilitas = ?'; $params[] = $filter['kolektibilitas']; }
        if (!empty($filter['jenis_kredit'])) { $where[] = 'k.jenis_kredit = ?'; $params[] = $filter['jenis_kredit']; }

        $whereStr = implode(' AND ', $where);
        $sql = "SELECT k.*, n.nama_lengkap, n.no_nasabah 
                FROM kredit k JOIN nasabah n ON k.nasabah_id = n.id 
                WHERE $whereStr ORDER BY k.created_at DESC LIMIT ? OFFSET ?";
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
            $where[] = '(k.no_kredit LIKE ? OR n.nama_lengkap LIKE ? OR k.jenis_kredit LIKE ?)';
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filter['status'])) { $where[] = 'k.status = ?'; $params[] = $filter['status']; }
        if (!empty($filter['kolektibilitas'])) { $where[] = 'k.kolektibilitas = ?'; $params[] = $filter['kolektibilitas']; }
        if (!empty($filter['jenis_kredit'])) { $where[] = 'k.jenis_kredit = ?'; $params[] = $filter['jenis_kredit']; }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM kredit k JOIN nasabah n ON k.nasabah_id = n.id WHERE $whereStr");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getDetail(int $id): ?array {
        $stmt = $this->db->prepare("SELECT k.*, n.nama_lengkap, n.no_nasabah FROM kredit k JOIN nasabah n ON k.nasabah_id = n.id WHERE k.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByNasabah(int $nasabahId): array {
        $stmt = $this->db->prepare("SELECT * FROM kredit WHERE nasabah_id = ? ORDER BY created_at DESC");
        $stmt->execute([$nasabahId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
