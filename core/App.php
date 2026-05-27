<?php
class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        $controllerMap = [
            'auth'       => 'AuthController',
            'dashboard'  => 'DashboardController',
            'nasabah'    => 'NasabahController',
            'rekening'   => 'RekeningController',
            'transaksi'  => 'TransaksiController',
            'kredit'     => 'KreditController',
            'laporan'    => 'LaporanController',
            'users'      => 'UserController',
            'profile'    => 'ProfileController',
        ];

        if ($url && isset($controllerMap[$url[0]])) {
            $ctrlClass = $controllerMap[$url[0]];
            $ctrlFile = '../app/controllers/' . $ctrlClass . '.php';
            if (file_exists($ctrlFile)) {
                require_once $ctrlFile;
                $this->controller = $ctrlClass;
                unset($url[0]);
            }
        } else if ($url && !isset($controllerMap[$url[0]])) {
            // Default to dashboard if unknown
            $ctrlFile = '../app/controllers/DashboardController.php';
            if (file_exists($ctrlFile)) {
                require_once $ctrlFile;
            }
        } else {
            $ctrlFile = '../app/controllers/DashboardController.php';
            if (file_exists($ctrlFile)) {
                require_once $ctrlFile;
            }
        }

        $ctrl = new $this->controller;

        if (isset($url[1]) && method_exists($ctrl, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$ctrl, $this->method], $this->params);
    }

    protected function parseUrl(): array {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
