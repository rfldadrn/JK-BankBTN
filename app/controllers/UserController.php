<?php
class UserController extends Controller {
    private User $model;

    public function __construct() {
        Auth::requireLogin();
        Auth::requireRole(['admin']);
        $this->model = new User();
    }

    public function index(): void {
        $search = $this->get('search', '');
        $page = max(1, (int)($this->get('page') ?? 1));
        $users = $this->model->getAllWithRoles($search, $page);
        $total = $this->model->countAll($search);
        $pagination = Helper::paginate($total, $page, PER_PAGE);

        $this->view('users.index', [
            'title' => 'Manajemen User',
            'users' => $users,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function create(): void {
        $db = Database::getInstance()->getConnection();
        $roles = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('users.create', [
            'title' => 'Tambah User Baru',
            'roles' => $roles,
        ]);
    }

    public function store(): void {
        Helper::verifyCsrf();

        $v = new Validation();
        if (!$v->validate($_POST, [
            'nama' => 'required|min:3|max:255',
            'username' => 'required|min:3|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|numeric',
        ])) {
            Session::setFlash('error', $v->firstError());
            Session::set('old_input', $_POST);
            $this->redirect('users/create');
        }

        $id = $this->model->insert([
            'nama' => Helper::sanitize($this->post('nama')),
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'password' => password_hash($this->post('password'), PASSWORD_BCRYPT, ['cost' => 12]),
            'nip' => $this->post('nip', ''),
            'jabatan' => Helper::sanitize($this->post('jabatan', '')),
            'no_telepon' => $this->post('no_telepon', ''),
        ]);

        $this->model->assignRole($id, (int)$this->post('role_id'));
        Helper::logActivity('CREATE_USER', 'users', $id);
        Session::remove('old_input');
        Session::setFlash('success', 'User berhasil ditambahkan.');
        $this->redirect('users');
    }

    public function edit($id = null): void {
        if (!$id) $this->redirect('users');
        $user = $this->model->getUserWithRole((int)$id);
        if (!$user) { Session::setFlash('error', 'User tidak ditemukan.'); $this->redirect('users'); }

        $db = Database::getInstance()->getConnection();
        $roles = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('users.edit', [
            'title' => 'Edit User',
            'editUser' => $user,
            'roles' => $roles,
        ]);
    }

    public function update($id = null): void {
        if (!$id) $this->redirect('users');
        Helper::verifyCsrf();

        $v = new Validation();
        $rules = [
            'nama' => 'required|min:3|max:255',
            'username' => "required|min:3|max:100|unique:users,username,$id",
            'email' => "required|email|unique:users,email,$id",
            'role_id' => 'required|numeric',
        ];
        if (!$v->validate($_POST, $rules)) {
            Session::setFlash('error', $v->firstError());
            $this->redirect('users/edit/' . $id);
        }

        $data = [
            'nama' => Helper::sanitize($this->post('nama')),
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'nip' => $this->post('nip', ''),
            'jabatan' => Helper::sanitize($this->post('jabatan', '')),
            'no_telepon' => $this->post('no_telepon', ''),
            'status' => $this->post('status', 'aktif'),
        ];

        if ($this->post('password')) {
            $data['password'] = password_hash($this->post('password'), PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $this->model->update((int)$id, $data);
        $this->model->assignRole((int)$id, (int)$this->post('role_id'));
        Helper::logActivity('UPDATE_USER', 'users', (int)$id);
        Session::setFlash('success', 'User berhasil diperbarui.');
        $this->redirect('users');
    }
}
