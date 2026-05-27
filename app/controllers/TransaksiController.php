<?php
class TransaksiController extends Controller {
    private Transaksi $model;

    public function __construct() {
        Auth::requireLogin();
        $this->model = new Transaksi();
    }

    public function index(): void {
        Auth::requireRole(['admin', 'cs', 'manager']);
        $filter = [
            'search' => $this->get('search', ''),
            'jenis_transaksi' => $this->get('jenis_transaksi', ''),
            'status' => $this->get('status', ''),
            'tanggal_dari' => $this->get('tanggal_dari', ''),
            'tanggal_sampai' => $this->get('tanggal_sampai', ''),
        ];
        $page = max(1, (int)($this->get('page') ?? 1));
        $data = $this->model->getAll($filter, $page);
        $total = $this->model->countFiltered($filter);
        $pagination = Helper::paginate($total, $page, PER_PAGE);

        $this->view('transaksi.index', [
            'title' => 'Data Transaksi',
            'transaksi' => $data,
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    public function create(): void {
        Auth::requireRole(['admin', 'cs']);
        $db = Database::getInstance()->getConnection();
        $rekening = $db->query("SELECT r.id, r.no_rekening, r.saldo, n.nama_lengkap FROM rekening r JOIN nasabah n ON r.nasabah_id = n.id WHERE r.status = 'aktif' ORDER BY n.nama_lengkap")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('transaksi.create', [
            'title' => 'Catat Transaksi Baru',
            'rekening_list' => $rekening,
        ]);
    }

    public function store(): void {
        Auth::requireRole(['admin', 'cs']);
        Helper::verifyCsrf();

        $v = new Validation();
        if (!$v->validate($_POST, [
            'rekening_id' => 'required|numeric',
            'jenis_transaksi' => 'required|in:setoran,penarikan,transfer_keluar',
            'jumlah' => 'required|numeric',
        ])) {
            Session::setFlash('error', $v->firstError());
            $this->redirect('transaksi/create');
        }

        $rekeningModel = new Rekening();
        $rekening = $rekeningModel->findById((int)$this->post('rekening_id'));
        if (!$rekening || $rekening['status'] !== 'aktif') {
            Session::setFlash('error', 'Rekening tidak valid atau tidak aktif.');
            $this->redirect('transaksi/create');
        }

        $jumlah = (float)$this->post('jumlah');
        $jenis = $this->post('jenis_transaksi');
        $saldoSebelum = (float)$rekening['saldo'];
        $saldoSesudah = $saldoSebelum;

        if ($jenis === 'setoran') {
            $saldoSesudah = $saldoSebelum + $jumlah;
        } elseif ($jenis === 'penarikan') {
            if ($jumlah > $saldoSebelum) {
                Session::setFlash('error', 'Saldo tidak mencukupi.');
                $this->redirect('transaksi/create');
            }
            $saldoSesudah = $saldoSebelum - $jumlah;
        } elseif ($jenis === 'transfer_keluar') {
            if ($jumlah > $saldoSebelum) {
                Session::setFlash('error', 'Saldo tidak mencukupi.');
                $this->redirect('transaksi/create');
            }
            $saldoSesudah = $saldoSebelum - $jumlah;

            // Credit target account
            $targetId = (int)$this->post('rekening_tujuan_id');
            if ($targetId) {
                $target = $rekeningModel->findById($targetId);
                if ($target && $target['status'] === 'aktif') {
                    $rekeningModel->update($targetId, ['saldo' => (float)$target['saldo'] + $jumlah]);
                    // Record incoming transfer
                    $this->model->insert([
                        'no_transaksi' => Helper::generateNoTransaksi(),
                        'rekening_id' => $targetId,
                        'rekening_tujuan_id' => (int)$this->post('rekening_id'),
                        'jenis_transaksi' => 'transfer_masuk',
                        'jumlah' => $jumlah,
                        'saldo_sebelum' => (float)$target['saldo'],
                        'saldo_sesudah' => (float)$target['saldo'] + $jumlah,
                        'keterangan' => 'Transfer masuk dari ' . $rekening['no_rekening'],
                        'tanggal_transaksi' => date('Y-m-d H:i:s'),
                        'teller_id' => Auth::id(),
                        'status' => 'sukses',
                    ]);
                }
            }
        }

        // Update saldo rekening sumber
        $rekeningModel->update((int)$this->post('rekening_id'), ['saldo' => $saldoSesudah]);

        $id = $this->model->insert([
            'no_transaksi' => Helper::generateNoTransaksi(),
            'rekening_id' => (int)$this->post('rekening_id'),
            'rekening_tujuan_id' => $this->post('rekening_tujuan_id') ?: null,
            'jenis_transaksi' => $jenis,
            'jumlah' => $jumlah,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSesudah,
            'keterangan' => Helper::sanitize($this->post('keterangan', '')),
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'teller_id' => Auth::id(),
            'status' => 'sukses',
        ]);

        Helper::logActivity('CREATE_TRANSAKSI', 'transaksi', $id);
        Session::setFlash('success', 'Transaksi berhasil dicatat.');
        $this->redirect('transaksi/detail/' . $id);
    }

    public function detail($id = null): void {
        if (!$id) $this->redirect('transaksi');
        $trx = $this->model->getDetail((int)$id);
        if (!$trx) { Session::setFlash('error', 'Transaksi tidak ditemukan.'); $this->redirect('transaksi'); }
        $this->view('transaksi.detail', [
            'title' => 'Detail Transaksi',
            'transaksi' => $trx,
        ]);
    }
}
