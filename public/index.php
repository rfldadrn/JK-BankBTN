<?php
session_start();

// Autoload configs
require_once __DIR__ . '/../app/config/Config.php';
require_once __DIR__ . '/../app/config/Database.php';

// Core
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/App.php';

// Helpers
require_once __DIR__ . '/../app/helpers/Session.php';
require_once __DIR__ . '/../app/helpers/Auth.php';
require_once __DIR__ . '/../app/helpers/Helper.php';
require_once __DIR__ . '/../app/helpers/Validation.php';
require_once __DIR__ . '/../app/helpers/Upload.php';

// Models
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Nasabah.php';
require_once __DIR__ . '/../app/models/Rekening.php';
require_once __DIR__ . '/../app/models/Transaksi.php';
require_once __DIR__ . '/../app/models/Kredit.php';
require_once __DIR__ . '/../app/models/Angsuran.php';

// Controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/NasabahController.php';
require_once __DIR__ . '/../app/controllers/RekeningController.php';
require_once __DIR__ . '/../app/controllers/TransaksiController.php';
require_once __DIR__ . '/../app/controllers/KreditController.php';
require_once __DIR__ . '/../app/controllers/LaporanController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';

// Session timeout check
if (Auth::check()) {
    $lastActivity = Session::get('last_activity');
    if ($lastActivity && (time() - $lastActivity) > SESSION_TIMEOUT) {
        session_destroy();
        session_start();
        Session::setFlash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
    Session::set('last_activity', time());
}

// Initialize app
$app = new App();
