<?php
class KreditController extends Controller {
    private Kredit $model;

    public function __construct() {
        Auth::requireLogin();
        $this->model = new Kredit();
    }

    public function index(): void {
        Auth::requireRole(['admin', 'backoffice', 'manager']);
        $filter = [
            'search' => $this->get('search', ''),
            'status' => $this->get('status', ''),
            'kolektibilitas' => $this->get('kolektibilitas', ''),
            'jenis_kredit' => $this->get('jenis_kredit', ''),
        ];
        $page = max(1, (int)($this->get('page') ?? 1));
        $data = $this->model->getAll($filter, $page);
        $total = $this->model->countFiltered($filter);
        $pagination = Helper::paginate($total, $page, PER_PAGE);

        $this->view('kredit.index', [
            'title' => 'Data Kredit',
            'kredit' => $data,
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    public function create(): void {
        Auth::requireRole(['admin', 'backoffice']);
        $db = Database::getInstance()->getConnection();
        $nasabah = $db->query("SELECT id, no_nasabah, nama_lengkap FROM nasabah WHERE status = 'aktif' AND deleted_at IS NULL ORDER BY nama_lengkap")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('kredit.create', [
            'title' => 'Input Kredit Baru',
            'nasabah_list' => $nasabah,
        ]);
    }

    public function store(): void {
        Auth::requireRole(['admin', 'backoffice']);
        Helper::verifyCsrf();

        $v = new Validation();
        if (!$v->validate($_POST, [
            'nasabah_id' => 'required|numeric',
            'jenis_kredit' => 'required',
            'plafon' => 'required|numeric',
            'suku_bunga' => 'required|numeric',
            'tenor_bulan' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
        ])) {
            Session::setFlash('error', $v->firstError());
            $this->redirect('kredit/create');
        }

        $plafon = (float)$this->post('plafon');
        $bunga = (float)$this->post('suku_bunga');
        $tenor = (int)$this->post('tenor_bulan');
        $bungaBulanan = $bunga / 100 / 12;
        $angsuran = $plafon * ($bungaBulanan * pow(1 + $bungaBulanan, $tenor)) / (pow(1 + $bungaBulanan, $tenor) - 1);

        $tanggalMulai = $this->post('tanggal_mulai');
        $tanggalJatuhTempo = date('Y-m-d', strtotime("+$tenor months", strtotime($tanggalMulai)));

        $id = $this->model->insert([
            'no_kredit' => Helper::generateNoKredit($this->post('jenis_kredit')),
            'nasabah_id' => (int)$this->post('nasabah_id'),
            'jenis_kredit' => Helper::sanitize($this->post('jenis_kredit')),
            'plafon' => $plafon,
            'outstanding' => $plafon,
            'suku_bunga' => $bunga,
            'tenor_bulan' => $tenor,
            'angsuran_per_bulan' => round($angsuran, 2),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            'tujuan_kredit' => Helper::sanitize($this->post('tujuan_kredit', '')),
            'jaminan' => Helper::sanitize($this->post('jaminan', '')),
            'created_by' => Auth::id(),
        ]);

        // Generate jadwal angsuran
        $angsuranModel = new Angsuran();
        $angsuranModel->generateJadwal($id, $plafon, $bunga, $tenor, $tanggalMulai);

        Helper::logActivity('CREATE_KREDIT', 'kredit', $id);
        Session::setFlash('success', 'Data kredit berhasil disimpan.');
        $this->redirect('kredit/detail/' . $id);
    }

    public function detail($id = null): void {
        if (!$id) $this->redirect('kredit');
        $kredit = $this->model->getDetail((int)$id);
        if (!$kredit) { Session::setFlash('error', 'Kredit tidak ditemukan.'); $this->redirect('kredit'); }

        $angsuranModel = new Angsuran();
        $this->view('kredit.detail', [
            'title' => 'Detail Kredit',
            'kredit' => $kredit,
            'angsuran' => $angsuranModel->getByKredit((int)$id),
        ]);
    }

    public function updateStatus($id = null): void {
        Auth::requireRole(['admin', 'backoffice']);
        if (!$id) $this->redirect('kredit');
        Helper::verifyCsrf();

        $kolektibilitas = $this->post('kolektibilitas');
        $status = $this->post('status');
        $data = [];
        if ($kolektibilitas) $data['kolektibilitas'] = $kolektibilitas;
        if ($status) $data['status'] = $status;
        if (!empty($data)) {
            $this->model->update((int)$id, $data);
            Helper::logActivity('UPDATE_KREDIT', 'kredit', (int)$id);
        }
        Session::setFlash('success', 'Status kredit berhasil diperbarui.');
        $this->redirect('kredit/detail/' . $id);
    }

    public function bayarAngsuran($id = null): void {
        Auth::requireRole(['admin', 'backoffice']);
        if (!$id) $this->redirect('kredit');
        Helper::verifyCsrf();

        $angsuranId = (int)$this->post('angsuran_id');
        $angsuranModel = new Angsuran();
        $angsuranModel->update($angsuranId, [
            'tanggal_bayar' => date('Y-m-d'),
            'status' => 'lunas',
        ]);

        // Update outstanding
        $angsuran = $angsuranModel->findById($angsuranId);
        if ($angsuran) {
            $kredit = $this->model->findById($angsuran['kredit_id']);
            if ($kredit) {
                $newOutstanding = max(0, (float)$kredit['outstanding'] - (float)$angsuran['pokok']);
                $this->model->update($kredit['id'], ['outstanding' => $newOutstanding]);
            }
        }

        Helper::logActivity('BAYAR_ANGSURAN', 'angsuran', $angsuranId);
        Session::setFlash('success', 'Pembayaran angsuran berhasil dicatat.');
        $this->redirect('kredit/detail/' . $id);
    }
}
