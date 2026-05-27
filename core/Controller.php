<?php
class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewFile = '../app/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            die("View [$view] tidak ditemukan.");
        }
        require_once $viewFile;
    }

    protected function redirect(string $url): void {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }

    protected function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function post(string $key, $default = null) {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function get(string $key, $default = null) {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }
}
