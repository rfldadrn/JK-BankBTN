<?php
class ProfileController extends Controller {
    public function __construct() {
        Auth::requireLogin();
    }

    public function index(): void {
        $userModel = new User();
        $user = $userModel->getUserWithRole(Auth::id());
        $this->view('profile.index', [
            'title' => 'Profil Saya',
            'profile' => $user,
        ]);
    }

    public function update(): void {
        Helper::verifyCsrf();
        $userModel = new User();
        $id = Auth::id();

        $data = [
            'nama' => Helper::sanitize($this->post('nama')),
            'email' => $this->post('email'),
            'no_telepon' => $this->post('no_telepon', ''),
            'jabatan' => Helper::sanitize($this->post('jabatan', '')),
        ];

        // Upload foto
        try {
            if (!empty($_FILES['foto']['name'])) {
                $data['foto'] = Upload::file('foto', 'foto_profil');
            }
        } catch (Exception $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('profile');
        }

        $userModel->update($id, $data);
        // Update session
        $updatedUser = $userModel->getUserWithRole($id);
        Auth::login($updatedUser);

        Helper::logActivity('UPDATE_PROFILE', 'users', $id);
        Session::setFlash('success', 'Profil berhasil diperbarui.');
        $this->redirect('profile');
    }

    public function changePassword(): void {
        Helper::verifyCsrf();
        $userModel = new User();
        $user = $userModel->findById(Auth::id());

        if (!password_verify($this->post('current_password'), $user['password'])) {
            Session::setFlash('error', 'Password lama tidak sesuai.');
            $this->redirect('profile');
        }

        if (strlen($this->post('new_password')) < 6) {
            Session::setFlash('error', 'Password baru minimal 6 karakter.');
            $this->redirect('profile');
        }

        if ($this->post('new_password') !== $this->post('confirm_password')) {
            Session::setFlash('error', 'Konfirmasi password tidak cocok.');
            $this->redirect('profile');
        }

        $userModel->update(Auth::id(), [
            'password' => password_hash($this->post('new_password'), PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        Helper::logActivity('CHANGE_PASSWORD', 'users', Auth::id());
        Session::setFlash('success', 'Password berhasil diubah.');
        $this->redirect('profile');
    }
}
