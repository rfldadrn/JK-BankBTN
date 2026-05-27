<?php
class RekeningController extends Controller {
    private Rekening $model;

    public function __construct() {
        Auth::requireLogin();
        $this->model = new Rekening();
    }

    public function index(): void {
        Auth::requireRole(['admin', 'cs', 'backoffice']);
        $filter = [
            'search' => $this->get('search', ''),
            'status' => $this->get('status', ''),
            'jenis_rekening' => $this->get('jenis_rekening', ''),
        ];
        $page = max(1, (int)($this->get('page') ?? 1));
        $data = $this->model->getAll($filter, $page);
        $total = $this->model->countFiltered($filter);
        $pagination = Helper::paginate($total, $page, PER_PAGE);

        $this->view('rekening.index', [
            'title' => 'Data Rekening',
            'rekening' => $data,
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    public function create(): void {
        Auth::requireRole(['admin', 'cs', 'backoffice']);
        $db = Database::getInstance()->getConnection();
        $nasabah = $db->query("SELECT id, no_nasabah, nama_lengkap FROM nasabah WHERE status = 'aktif' AND deleted_at IS NULL ORDER BY nama_lengkap")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('rekening.create', [
            'title' => 'Buka Rekening Baru',
            'nasabah_list' => $nasabah,
        ]);
    }

    public function store(): void {
        Auth::requireRole(['admin', 'cs', 'backoffice']);
        Helper::verifyCsrf();

        $v = new Validation();
        if (!$v->validate($_POST, [
            'nasabah_id' => 'required|numeric',
            'jenis_rekening' => 'required|in:tabungan,giro,deposito,batara',
            'saldo' => 'required|numeric',
        ])) {
            Session::setFlash('error', $v->firstError());
            $this->redirect('rekening/create');
        }

        $id = $this->model->insert([
            'no_rekening' => Helper::generateNoRekening(),
            'nasabah_id' => (int)$this->post('nasabah_id'),
            'jenis_rekening' => $this->post('jenis_rekening'),
            'nama_produk' => Helper::sanitize($this->post('nama_produk', '')),
            'saldo' => (float)$this->post('saldo'),
            'tanggal_buka' => date('Y-m-d'),
            'keterangan' => Helper::sanitize($this->post('keterangan', '')),
            'created_by' => Auth::id(),
        ]);

        Helper::logActivity('CREATE_REKENING', 'rekening', $id);
        Session::setFlash('success', 'Rekening berhasil dibuka.');
        $this->redirect('rekening');
    }

    public function detail($id = null): void {
        if (!$id) $this->redirect('rekening');
        $rekening = $this->model->getDetail((int)$id);
        if (!$rekening) { Session::setFlash('error', 'Rekening tidak ditemukan.'); $this->redirect('rekening'); }

        $trxModel = new Transaksi();
        $this->view('rekening.detail', [
            'title' => 'Detail Rekening',
            'rekening' => $rekening,
            'transaksi' => $trxModel->getByRekening((int)$id, 20),
        ]);
    }

    public function updateStatus($id = null): void {
        Auth::requireRole(['admin', 'backoffice']);
        if (!$id) $this->redirect('rekening');
        Helper::verifyCsrf();
        $status = $this->post('status');
        if (!in_array($status, ['aktif', 'beku', 'tutup'])) {
            Session::setFlash('error', 'Status tidak valid.');
            $this->redirect('rekening/detail/' . $id);
        }
        $data = ['status' => $status];
        if ($status === 'tutup') $data['tanggal_tutup'] = date('Y-m-d');
        $this->model->update((int)$id, $data);
        Helper::logActivity('UPDATE_STATUS_REKENING', 'rekening', (int)$id);
        Session::setFlash('success', 'Status rekening berhasil diperbarui.');
        $this->redirect('rekening/detail/' . $id);
    }
}
