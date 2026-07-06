<?php
class NasabahController extends Controller {
    private Nasabah $model;

    public function __construct() {
        Auth::requireLogin();
        $this->model = new Nasabah();
    }

    public function index(): void {
        Auth::requireRole(['admin', 'cs', 'backoffice', 'manager']);
        $filter = [
            'search' => $this->get('search', ''),
            'status' => $this->get('status', ''),
            'jenis_kelamin' => $this->get('jenis_kelamin', ''),
            'segmen' => $this->get('segmen', ''),
        ];
        $page = max(1, (int)($this->get('page') ?? 1));
        $data = $this->model->getAll($filter, $page);
        $total = $this->model->countFiltered($filter);
        $pagination = Helper::paginate($total, $page, PER_PAGE);

        $this->view('nasabah.index', [
            'title' => 'Data Nasabah',
            'nasabah' => $data,
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    public function create(): void {
        Auth::requireRole(['admin', 'cs']);
        $this->view('nasabah.create', ['title' => 'Tambah Nasabah Baru']);
    }

    public function store(): void {
        Auth::requireRole(['admin', 'cs']);
        Helper::verifyCsrf();

        $v = new Validation();
        if (!$v->validate($_POST, [
            'nik' => 'required|exact:16|numeric',
            'nama_lengkap' => 'required|min:3|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'required|min:10|max:15',
            'alamat' => 'required|min:10',
            'kota_kabupaten' => 'required',
            'provinsi' => 'required',
        ])) {
            Session::setFlash('error', $v->firstError());
            Session::set('old_input', $_POST);
            $this->redirect('nasabah/create');
        }

        // Check duplicate NIK
        if ($this->model->checkDuplicate($this->post('nik'))) {
            Session::setFlash('error', 'NIK sudah terdaftar dalam sistem.');
            Session::set('old_input', $_POST);
            $this->redirect('nasabah/create');
        }

        $ktpPath = null;
        try {
            if (!empty($_FILES['file_ktp']['name'])) {
                $ktpPath = Upload::file('file_ktp', 'ktp');
            }
        } catch (Exception $e) {
            Session::setFlash('error', $e->getMessage());
            Session::set('old_input', $_POST);
            $this->redirect('nasabah/create');
        }

        $id = $this->model->insert([
            'no_nasabah' => Helper::generateNoNasabah(),
            'nik' => $this->post('nik'),
            'nama_lengkap' => Helper::sanitize($this->post('nama_lengkap')),
            'tempat_lahir' => Helper::sanitize($this->post('tempat_lahir', '')),
            'tanggal_lahir' => $this->post('tanggal_lahir'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'agama' => $this->post('agama', ''),
            'status_perkawinan' => $this->post('status_perkawinan', 'lajang'),
            'pekerjaan' => Helper::sanitize($this->post('pekerjaan', '')),
            'penghasilan_bulanan' => $this->post('penghasilan_bulanan') ?: null,
            'no_telepon' => $this->post('no_telepon'),
            'email' => $this->post('email', ''),
            'alamat' => Helper::sanitize($this->post('alamat')),
            'rt_rw' => $this->post('rt_rw', ''),
            'kelurahan' => Helper::sanitize($this->post('kelurahan', '')),
            'kecamatan' => Helper::sanitize($this->post('kecamatan', '')),
            'kota_kabupaten' => Helper::sanitize($this->post('kota_kabupaten')),
            'provinsi' => Helper::sanitize($this->post('provinsi')),
            'kode_pos' => $this->post('kode_pos', ''),
            'no_ktp_path' => $ktpPath,
            'created_by' => Auth::id(),
        ]);

        Helper::logActivity('CREATE_NASABAH', 'nasabah', $id);
        Session::remove('old_input');
        Session::setFlash('success', 'Data nasabah berhasil ditambahkan.');
        $this->redirect('nasabah/detail/' . $id);
    }

    public function detail($id = null): void {
        if (!$id) $this->redirect('nasabah');
        $nasabah = $this->model->getDetail((int)$id);
        if (!$nasabah) { Session::setFlash('error', 'Nasabah tidak ditemukan.'); $this->redirect('nasabah'); }

        $rekModel = new Rekening();
        $kreditModel = new Kredit();

        $this->view('nasabah.detail', [
            'title' => 'Detail Nasabah',
            'nasabah' => $nasabah,
            'rekening' => $rekModel->getByNasabah((int)$id),
            'kredit' => $kreditModel->getByNasabah((int)$id),
        ]);
    }

    public function edit($id = null): void {
        Auth::requireRole(['admin', 'cs']);
        if (!$id) $this->redirect('nasabah');
        $nasabah = $this->model->getDetail((int)$id);
        if (!$nasabah) { Session::setFlash('error', 'Nasabah tidak ditemukan.'); $this->redirect('nasabah'); }

        $this->view('nasabah.edit', [
            'title' => 'Edit Nasabah',
            'nasabah' => $nasabah,
        ]);
    }

    public function update($id = null): void {
        Auth::requireRole(['admin', 'cs']);
        if (!$id) $this->redirect('nasabah');
        Helper::verifyCsrf();

        $v = new Validation();
        if (!$v->validate($_POST, [
            'nik' => 'required|exact:16|numeric',
            'nama_lengkap' => 'required|min:3|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'required|min:10|max:15',
            'alamat' => 'required|min:10',
            'kota_kabupaten' => 'required',
            'provinsi' => 'required',
        ])) {
            Session::setFlash('error', $v->firstError());
            $this->redirect('nasabah/edit/' . $id);
        }

        if ($this->model->checkDuplicate($this->post('nik'), (int)$id)) {
            Session::setFlash('error', 'NIK sudah terdaftar untuk nasabah lain.');
            $this->redirect('nasabah/edit/' . $id);
        }

        $data = [
            'nik' => $this->post('nik'),
            'nama_lengkap' => Helper::sanitize($this->post('nama_lengkap')),
            'tempat_lahir' => Helper::sanitize($this->post('tempat_lahir', '')),
            'tanggal_lahir' => $this->post('tanggal_lahir'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'agama' => $this->post('agama', ''),
            'status_perkawinan' => $this->post('status_perkawinan', 'lajang'),
            'pekerjaan' => Helper::sanitize($this->post('pekerjaan', '')),
            'penghasilan_bulanan' => $this->post('penghasilan_bulanan') ?: null,
            'no_telepon' => $this->post('no_telepon'),
            'email' => $this->post('email', ''),
            'alamat' => Helper::sanitize($this->post('alamat')),
            'rt_rw' => $this->post('rt_rw', ''),
            'kelurahan' => Helper::sanitize($this->post('kelurahan', '')),
            'kecamatan' => Helper::sanitize($this->post('kecamatan', '')),
            'kota_kabupaten' => Helper::sanitize($this->post('kota_kabupaten')),
            'provinsi' => Helper::sanitize($this->post('provinsi')),
            'kode_pos' => $this->post('kode_pos', ''),
            'updated_by' => Auth::id(),
        ];

        try {
            if (!empty($_FILES['file_ktp']['name'])) {
                $data['no_ktp_path'] = Upload::file('file_ktp', 'ktp');
            }
        } catch (Exception $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('nasabah/edit/' . $id);
        }

        $this->model->update((int)$id, $data);
        Helper::logActivity('UPDATE_NASABAH', 'nasabah', (int)$id);
        Session::setFlash('success', 'Data nasabah berhasil diperbarui.');
        $this->redirect('nasabah/detail/' . $id);
    }

    public function delete($id = null): void {
        Auth::requireRole(['admin']);
        if (!$id) $this->redirect('nasabah');
        Helper::verifyCsrf();
        $this->model->softDelete((int)$id);
        Helper::logActivity('DELETE_NASABAH', 'nasabah', (int)$id);
        Session::setFlash('success', 'Nasabah berhasil dinonaktifkan.');
        $this->redirect('nasabah');
    }
}
