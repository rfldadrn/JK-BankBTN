<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        btn: { primary: '#003087', secondary: '#0066CC', accent: '#FF6600', light: '#E8F0FE' }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link.active { background: #0066CC; color: white; }
        .sidebar-link:hover { background: rgba(0,102,204,0.1); }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex min-h-screen">
    <?php require_once '../app/views/layouts/sidebar.php'; ?>
    <div class="flex-1 flex flex-col ml-64">
        <!-- Top Navbar -->
        <nav class="bg-white shadow-sm px-6 py-3 flex items-center justify-between sticky top-0 z-30 no-print">
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
                <h1 class="font-semibold text-gray-800 text-lg"><?= $title ?? '' ?></h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-2 text-sm text-gray-600 hover:text-btn-primary">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span><?= htmlspecialchars(Auth::user()['nama'] ?? '') ?></span>
                </a>
                <a href="<?= BASE_URL ?>/auth/logout" class="text-sm text-red-500 hover:text-red-700">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </nav>

        <!-- Flash messages -->
        <?php foreach (['success','error','info','warning'] as $type):
            $msg = Session::getFlash($type); if (!$msg) continue;
            $colors = ['success'=>'green','error'=>'red','info'=>'blue','warning'=>'yellow'];
            $c = $colors[$type]; ?>
        <div class="mx-6 mt-4 p-3 rounded-lg bg-<?=$c?>-50 text-<?=$c?>-800 border border-<?=$c?>-200 text-sm flex items-center gap-2">
            <i class="fas fa-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
            <?= htmlspecialchars($msg) ?>
        </div>
        <?php endforeach; ?>

        <main class="flex-1 p-6">
