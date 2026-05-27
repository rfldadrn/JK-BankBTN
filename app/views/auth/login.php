<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-[#003087] to-[#0066CC] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <!-- Header -->
        <div class="bg-[#003087] p-8 text-center">
            <img src="<?= BASE_URL ?>/assets/img/logo-btn.png" alt="Logo Bank BTN" class="w-20 h-20 mx-auto mb-4 rounded-full bg-white p-2" onerror="this.outerHTML='<div class=\'w-20 h-20 mx-auto mb-4 rounded-full bg-white flex items-center justify-center\'><span class=\'text-[#003087] font-bold text-xl\'>BTN</span></div>'">
            <h1 class="text-white text-xl font-bold">Sistem Informasi</h1>
            <p class="text-blue-200 text-sm mt-1">Pengelolaan Data Nasabah</p>
            <p class="text-blue-300 text-xs mt-1">Kantor Cabang Pekanbaru</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            <?php $error = Session::getFlash('error'); if ($error): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['timeout'])): ?>
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-clock"></i> Sesi Anda telah berakhir. Silakan login kembali.
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/auth/login">
                <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="username" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#003087] focus:border-transparent outline-none"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#003087] focus:border-transparent outline-none"
                            placeholder="Masukkan password">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-[#003087] text-white py-3 rounded-lg font-semibold hover:bg-[#002060] transition-colors shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-4 text-center border-t">
            <p class="text-xs text-gray-500">&copy; <?= date('Y') ?> Bank BTN KC Pekanbaru</p>
            <p class="text-xs text-gray-400 mt-1">v1.0 — Sistem Internal</p>
        </div>
    </div>

    <script>
    function togglePassword() {
        const pw = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (pw.type === 'password') { pw.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
        else { pw.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
    }
    </script>
</body>
</html>
