<?php
// APP
define('APP_NAME', 'Sistem Informasi Pengelolaan Data Nasabah');
define('APP_SHORT', 'SIPDN BTN');
define('BASE_URL', 'http://localhost/PengolahanDataBanking/public');
define('UPLOAD_PATH', __DIR__ . '/../../public/uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');

// DATABASE
define('DB_HOST', 'localhost');
define('DB_NAME', 'pengelolaan_data_banking');
define('DB_USER', 'root');
define('DB_PASS', '');

// SESSION
define('SESSION_NAME', 'btn_session');
define('SESSION_TIMEOUT', 1800); // 30 menit

// UPLOAD
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']);

// PAGINATION
define('PER_PAGE', 25);

// BANK BTN COLORS
define('BTN_PRIMARY', '#003087');
define('BTN_SECONDARY', '#0066CC');
define('BTN_ACCENT', '#FF6600');
