<?php
class Nasabah extends Model {
    protected $table = 'nasabah';

    private function segmentKeyCaseSql(string $aumExpr = 'COALESCE(a.total_aum, 0)'): string {
        return "CASE
                    WHEN {$aumExpr} < 10000000 THEN 'mass'
                    WHEN {$aumExpr} >= 10000000 AND {$aumExpr} < 100000000 THEN 'prima'
                    WHEN {$aumExpr} >= 100000000 AND {$aumExpr} < 500000000 THEN 'prospera'
                    WHEN {$aumExpr} >= 500000000 AND {$aumExpr} <= 15000000000 THEN 'prioritas'
                    ELSE 'private'
                END";
    }

    private function segmentLabelCaseSql(string $aumExpr = 'COALESCE(a.total_aum, 0)'): string {
        return "CASE
                    WHEN {$aumExpr} < 10000000 THEN 'Mass Segment'
                    WHEN {$aumExpr} >= 10000000 AND {$aumExpr} < 100000000 THEN 'BTN Prima'
                    WHEN {$aumExpr} >= 100000000 AND {$aumExpr} < 500000000 THEN 'BTN Prospera'
                    WHEN {$aumExpr} >= 500000000 AND {$aumExpr} <= 15000000000 THEN 'BTN Prioritas'
                    ELSE 'BTN Private'
                END";
    }

    private function segmentDescriptionCaseSql(string $aumExpr = 'COALESCE(a.total_aum, 0)'): string {
        return "CASE
                    WHEN {$aumExpr} < 10000000 THEN 'Segmen terbawah, nasabah ritel biasa'
                    WHEN {$aumExpr} >= 10000000 AND {$aumExpr} < 100000000 THEN 'Upper mass segment'
                    WHEN {$aumExpr} >= 100000000 AND {$aumExpr} < 500000000 THEN 'Emerging affluent (target utama program Prospera)'
                    WHEN {$aumExpr} >= 500000000 AND {$aumExpr} <= 15000000000 THEN 'Nasabah prioritas dengan layanan wealth management'
                    ELSE 'Nasabah super kaya (high-net-worth individual)'
                END";
    }

    public function getAll(array $filter = [], int $page = 1): array {
        $offset = ($page - 1) * PER_PAGE;
        $where = ['n.deleted_at IS NULL'];
        $params = [];
        $segmentKeyCase = $this->segmentKeyCaseSql();
        $segmentLabelCase = $this->segmentLabelCaseSql();
        $segmentDescriptionCase = $this->segmentDescriptionCaseSql();

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
        if (!empty($filter['segmen'])) {
            $where[] = "{$segmentKeyCase} = ?";
            $params[] = $filter['segmen'];
        }

        $whereStr = implode(' AND ', $where);
        $sql = "SELECT n.*, COALESCE(a.total_aum, 0) AS total_aum,
                       {$segmentKeyCase} AS segment_key,
                       {$segmentLabelCase} AS segment_label,
                       {$segmentDescriptionCase} AS segment_description
                FROM nasabah n
                LEFT JOIN (
                    SELECT nasabah_id, COALESCE(SUM(saldo), 0) AS total_aum
                    FROM rekening
                    GROUP BY nasabah_id
                ) a ON a.nasabah_id = n.id
                WHERE $whereStr
                ORDER BY n.created_at DESC LIMIT ? OFFSET ?";
        $params[] = PER_PAGE;
        $params[] = $offset;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered(array $filter = []): int {
        $where = ['n.deleted_at IS NULL'];
        $params = [];
        $segmentKeyCase = $this->segmentKeyCaseSql('COALESCE(a.total_aum, 0)');
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
        if (!empty($filter['segmen'])) {
            $where[] = "{$segmentKeyCase} = ?";
            $params[] = $filter['segmen'];
        }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*)
                                    FROM nasabah n
                                    LEFT JOIN (
                                        SELECT nasabah_id, COALESCE(SUM(saldo), 0) AS total_aum
                                        FROM rekening
                                        GROUP BY nasabah_id
                                    ) a ON a.nasabah_id = n.id
                                    WHERE $whereStr");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getDetail(int $id): ?array {
        $segmentKeyCase = $this->segmentKeyCaseSql();
        $segmentLabelCase = $this->segmentLabelCaseSql();
        $segmentDescriptionCase = $this->segmentDescriptionCaseSql();
        $stmt = $this->db->prepare("SELECT n.*, COALESCE(a.total_aum, 0) AS total_aum,
                                           {$segmentKeyCase} AS segment_key,
                                           {$segmentLabelCase} AS segment_label,
                                           {$segmentDescriptionCase} AS segment_description
                                    FROM nasabah n
                                    LEFT JOIN (
                                        SELECT nasabah_id, COALESCE(SUM(saldo), 0) AS total_aum
                                        FROM rekening
                                        GROUP BY nasabah_id
                                    ) a ON a.nasabah_id = n.id
                                    WHERE n.id = ? AND n.deleted_at IS NULL");
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
