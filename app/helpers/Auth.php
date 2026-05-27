<?php
class Auth {
    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    public static function role(): ?string {
        return $_SESSION['user']['role_name'] ?? null;
    }

    public static function id(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            Session::setFlash('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            session_destroy();
            header('Location: ' . BASE_URL . '/auth/login?timeout=1');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }

    public static function requireRole(array $roles): void {
        self::requireLogin();
        if (!in_array(self::role(), $roles)) {
            http_response_code(403);
            die('<div style="text-align:center;margin-top:100px;font-family:Arial;">
                <h1>403 - Akses Ditolak</h1>
                <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                <a href="' . BASE_URL . '/dashboard">Kembali ke Dashboard</a>
            </div>');
        }
    }

    public static function hasRole(string $role): bool {
        return self::role() === $role;
    }

    public static function login(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['last_activity'] = time();
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
    }
}
