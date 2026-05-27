<?php
class Transaksi extends Model {
    protected $table = 'transaksi';

    public function getAll(array $filter = [], int $page = 1): array {
        $offset = ($page - 1) * PER_PAGE;
        $where = ['1=1'];
        $params = [];

        if (!empty($filter['search'])) {
            $s = '%' . $filter['search'] . '%';
            $where[] = '(t.no_transaksi LIKE ? OR r.no_rekening LIKE ? OR n.nama_lengkap LIKE ?)';
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filter['jenis_transaksi'])) {
            $where[] = 't.jenis_transaksi = ?';
            $params[] = $filter['jenis_transaksi'];
        }
        if (!empty($filter['status'])) {
            $where[] = 't.status = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['tanggal_dari'])) {
            $where[] = 't.tanggal_transaksi >= ?';
            $params[] = $filter['tanggal_dari'] . ' 00:00:00';
        }
        if (!empty($filter['tanggal_sampai'])) {
            $where[] = 't.tanggal_transaksi <= ?';
            $params[] = $filter['tanggal_sampai'] . ' 23:59:59';
        }
        if (!empty($filter['rekening_id'])) {
            $where[] = 't.rekening_id = ?';
            $params[] = $filter['rekening_id'];
        }

        $whereStr = implode(' AND ', $where);
        $sql = "SELECT t.*, r.no_rekening, n.nama_lengkap, u.nama as nama_teller
                FROM transaksi t 
                JOIN rekening r ON t.rekening_id = r.id 
                JOIN nasabah n ON r.nasabah_id = n.id 
                JOIN users u ON t.teller_id = u.id
                WHERE $whereStr 
                ORDER BY t.tanggal_transaksi DESC LIMIT ? OFFSET ?";
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
            $where[] = '(t.no_transaksi LIKE ? OR r.no_rekening LIKE ? OR n.nama_lengkap LIKE ?)';
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filter['jenis_transaksi'])) { $where[] = 't.jenis_transaksi = ?'; $params[] = $filter['jenis_transaksi']; }
        if (!empty($filter['status'])) { $where[] = 't.status = ?'; $params[] = $filter['status']; }
        if (!empty($filter['tanggal_dari'])) { $where[] = 't.tanggal_transaksi >= ?'; $params[] = $filter['tanggal_dari'] . ' 00:00:00'; }
        if (!empty($filter['tanggal_sampai'])) { $where[] = 't.tanggal_transaksi <= ?'; $params[] = $filter['tanggal_sampai'] . ' 23:59:59'; }
        if (!empty($filter['rekening_id'])) { $where[] = 't.rekening_id = ?'; $params[] = $filter['rekening_id']; }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM transaksi t JOIN rekening r ON t.rekening_id = r.id JOIN nasabah n ON r.nasabah_id = n.id WHERE $whereStr");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getDetail(int $id): ?array {
        $stmt = $this->db->prepare("SELECT t.*, r.no_rekening, n.nama_lengkap, n.no_nasabah, u.nama as nama_teller
            FROM transaksi t JOIN rekening r ON t.rekening_id = r.id 
            JOIN nasabah n ON r.nasabah_id = n.id JOIN users u ON t.teller_id = u.id WHERE t.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByRekening(int $rekeningId, int $limit = 10): array {
        $stmt = $this->db->prepare("SELECT t.*, u.nama as nama_teller FROM transaksi t JOIN users u ON t.teller_id = u.id WHERE t.rekening_id = ? ORDER BY t.tanggal_transaksi DESC LIMIT ?");
        $stmt->execute([$rekeningId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
