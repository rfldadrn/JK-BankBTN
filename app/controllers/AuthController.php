<?php
class AuthController extends Controller {
    public function login(): void {
        if (Auth::check()) { $this->redirect('dashboard'); }

        if ($this->isPost()) {
            Helper::verifyCsrf();
            $username = $this->post('username');
            $password = $this->post('password');

            $userModel = new User();
            $user = $userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                Auth::login($user);
                $userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
                Helper::logActivity('LOGIN');
                $this->redirect('dashboard');
            }
            Session::setFlash('error', 'Username atau password salah.');
        }
        $this->view('auth.login', ['title' => 'Login - ' . APP_NAME]);
    }

    public function logout(): void {
        Helper::logActivity('LOGOUT');
        Auth::logout();
        $this->redirect('auth/login');
    }
}
