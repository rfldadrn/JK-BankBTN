<?php
class DashboardController extends Controller {
    public function index(): void {
        Auth::requireLogin();
        $db = Database::getInstance()->getConnection();

        $data = [
            'title' => 'Dashboard',
            'user' => Auth::user(),
        ];

        // Stats
        $data['total_nasabah'] = (int)$db->query("SELECT COUNT(*) FROM nasabah WHERE deleted_at IS NULL AND status = 'aktif'")->fetchColumn();
        $data['total_rekening'] = (int)$db->query("SELECT COUNT(*) FROM rekening WHERE status = 'aktif'")->fetchColumn();
        $data['total_transaksi'] = (int)$db->query("SELECT COUNT(*) FROM transaksi WHERE MONTH(tanggal_transaksi) = MONTH(NOW()) AND YEAR(tanggal_transaksi) = YEAR(NOW())")->fetchColumn();
        $data['total_kredit'] = (int)$db->query("SELECT COUNT(*) FROM kredit WHERE status = 'aktif'")->fetchColumn();

        // Transaksi bulan ini
        $stmt = $db->query("SELECT DATE(tanggal_transaksi) as tanggal, SUM(jumlah) as total, jenis_transaksi 
            FROM transaksi WHERE tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND status = 'sukses'
            GROUP BY DATE(tanggal_transaksi), jenis_transaksi ORDER BY tanggal ASC");
        $data['chart_transaksi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nasabah terbaru
        $stmt = $db->query("SELECT * FROM nasabah WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5");
        $data['nasabah_terbaru'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Total saldo
        $data['total_saldo'] = (float)$db->query("SELECT COALESCE(SUM(saldo), 0) FROM rekening WHERE status = 'aktif'")->fetchColumn();

        // Kredit outstanding
        $data['total_outstanding'] = (float)$db->query("SELECT COALESCE(SUM(outstanding), 0) FROM kredit WHERE status = 'aktif'")->fetchColumn();

        $this->view('dashboard.index', $data);
    }
}
