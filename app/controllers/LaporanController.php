<?php
class LaporanController extends Controller {
    public function __construct() {
        Auth::requireLogin();
        Auth::requireRole(['admin', 'manager', 'auditor']);
    }

    public function index(): void {
        $this->view('laporan.index', ['title' => 'Laporan']);
    }

    public function nasabah(): void {
        $filter = [
            'status' => $this->get('status', ''),
            'jenis_kelamin' => $this->get('jenis_kelamin', ''),
            'tanggal_dari' => $this->get('tanggal_dari', ''),
            'tanggal_sampai' => $this->get('tanggal_sampai', ''),
        ];

        $db = Database::getInstance()->getConnection();
        $where = ['deleted_at IS NULL'];
        $params = [];

        if ($filter['status']) { $where[] = 'status = ?'; $params[] = $filter['status']; }
        if ($filter['jenis_kelamin']) { $where[] = 'jenis_kelamin = ?'; $params[] = $filter['jenis_kelamin']; }
        if ($filter['tanggal_dari']) { $where[] = 'created_at >= ?'; $params[] = $filter['tanggal_dari'] . ' 00:00:00'; }
        if ($filter['tanggal_sampai']) { $where[] = 'created_at <= ?'; $params[] = $filter['tanggal_sampai'] . ' 23:59:59'; }

        $whereStr = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT * FROM nasabah WHERE $whereStr ORDER BY created_at DESC");
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('laporan.nasabah', [
            'title' => 'Laporan Data Nasabah',
            'data' => $data,
            'filter' => $filter,
        ]);
    }

    public function transaksi(): void {
        $filter = [
            'jenis_transaksi' => $this->get('jenis_transaksi', ''),
            'tanggal_dari' => $this->get('tanggal_dari', ''),
            'tanggal_sampai' => $this->get('tanggal_sampai', ''),
        ];

        $db = Database::getInstance()->getConnection();
        $where = ['1=1'];
        $params = [];

        if ($filter['jenis_transaksi']) { $where[] = 't.jenis_transaksi = ?'; $params[] = $filter['jenis_transaksi']; }
        if ($filter['tanggal_dari']) { $where[] = 't.tanggal_transaksi >= ?'; $params[] = $filter['tanggal_dari'] . ' 00:00:00'; }
        if ($filter['tanggal_sampai']) { $where[] = 't.tanggal_transaksi <= ?'; $params[] = $filter['tanggal_sampai'] . ' 23:59:59'; }

        $whereStr = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT t.*, r.no_rekening, n.nama_lengkap FROM transaksi t 
            JOIN rekening r ON t.rekening_id = r.id JOIN nasabah n ON r.nasabah_id = n.id 
            WHERE $whereStr ORDER BY t.tanggal_transaksi DESC LIMIT 500");
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('laporan.transaksi', [
            'title' => 'Laporan Transaksi',
            'data' => $data,
            'filter' => $filter,
        ]);
    }

    public function kredit(): void {
        $filter = [
            'status' => $this->get('status', ''),
            'kolektibilitas' => $this->get('kolektibilitas', ''),
            'jenis_kredit' => $this->get('jenis_kredit', ''),
        ];

        $db = Database::getInstance()->getConnection();
        $where = ['1=1'];
        $params = [];

        if ($filter['status']) { $where[] = 'k.status = ?'; $params[] = $filter['status']; }
        if ($filter['kolektibilitas']) { $where[] = 'k.kolektibilitas = ?'; $params[] = $filter['kolektibilitas']; }
        if ($filter['jenis_kredit']) { $where[] = 'k.jenis_kredit = ?'; $params[] = $filter['jenis_kredit']; }

        $whereStr = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT k.*, n.nama_lengkap, n.no_nasabah FROM kredit k JOIN nasabah n ON k.nasabah_id = n.id WHERE $whereStr ORDER BY k.created_at DESC");
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('laporan.kredit', [
            'title' => 'Laporan Kredit',
            'data' => $data,
            'filter' => $filter,
        ]);
    }

    public function cetakNasabah(): void {
        $filter = [
            'status' => $this->get('status', ''),
            'jenis_kelamin' => $this->get('jenis_kelamin', ''),
            'tanggal_dari' => $this->get('tanggal_dari', ''),
            'tanggal_sampai' => $this->get('tanggal_sampai', ''),
        ];

        $db = Database::getInstance()->getConnection();
        $where = ['deleted_at IS NULL'];
        $params = [];

        if ($filter['status']) { $where[] = 'status = ?'; $params[] = $filter['status']; }
        if ($filter['jenis_kelamin']) { $where[] = 'jenis_kelamin = ?'; $params[] = $filter['jenis_kelamin']; }
        if ($filter['tanggal_dari']) { $where[] = 'created_at >= ?'; $params[] = $filter['tanggal_dari'] . ' 00:00:00'; }
        if ($filter['tanggal_sampai']) { $where[] = 'created_at <= ?'; $params[] = $filter['tanggal_sampai'] . ' 23:59:59'; }

        $whereStr = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT * FROM nasabah WHERE $whereStr ORDER BY created_at DESC");
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('laporan.cetak_nasabah', [
            'data' => $data,
            'filter' => $filter,
        ]);
    }

    public function cetakTransaksi(): void {
        $filter = [
            'jenis_transaksi' => $this->get('jenis_transaksi', ''),
            'tanggal_dari' => $this->get('tanggal_dari', ''),
            'tanggal_sampai' => $this->get('tanggal_sampai', ''),
        ];

        $db = Database::getInstance()->getConnection();
        $where = ['1=1'];
        $params = [];

        if ($filter['jenis_transaksi']) { $where[] = 't.jenis_transaksi = ?'; $params[] = $filter['jenis_transaksi']; }
        if ($filter['tanggal_dari']) { $where[] = 't.tanggal_transaksi >= ?'; $params[] = $filter['tanggal_dari'] . ' 00:00:00'; }
        if ($filter['tanggal_sampai']) { $where[] = 't.tanggal_transaksi <= ?'; $params[] = $filter['tanggal_sampai'] . ' 23:59:59'; }

        $whereStr = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT t.*, r.no_rekening, n.nama_lengkap FROM transaksi t 
            JOIN rekening r ON t.rekening_id = r.id JOIN nasabah n ON r.nasabah_id = n.id 
            WHERE $whereStr ORDER BY t.tanggal_transaksi DESC LIMIT 500");
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('laporan.cetak_transaksi', [
            'data' => $data,
            'filter' => $filter,
        ]);
    }

    public function cetakKredit(): void {
        $filter = [
            'status' => $this->get('status', ''),
            'kolektibilitas' => $this->get('kolektibilitas', ''),
            'jenis_kredit' => $this->get('jenis_kredit', ''),
        ];

        $db = Database::getInstance()->getConnection();
        $where = ['1=1'];
        $params = [];

        if ($filter['status']) { $where[] = 'k.status = ?'; $params[] = $filter['status']; }
        if ($filter['kolektibilitas']) { $where[] = 'k.kolektibilitas = ?'; $params[] = $filter['kolektibilitas']; }
        if ($filter['jenis_kredit']) { $where[] = 'k.jenis_kredit = ?'; $params[] = $filter['jenis_kredit']; }

        $whereStr = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT k.*, n.nama_lengkap, n.no_nasabah FROM kredit k JOIN nasabah n ON k.nasabah_id = n.id WHERE $whereStr ORDER BY k.created_at DESC");
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('laporan.cetak_kredit', [
            'data' => $data,
            'filter' => $filter,
        ]);
    }
}
