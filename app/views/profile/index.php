<?php require_once '../app/views/layouts/header.php'; $u = $profile; ?>
<div class="max-w-3xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-btn-primary rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto"><?= strtoupper(substr($u['nama'], 0, 2)) ?></div>
                <h3 class="mt-3 font-bold text-gray-800"><?= htmlspecialchars($u['nama']) ?></h3>
                <p class="text-sm text-gray-500">@<?= htmlspecialchars($u['username']) ?></p>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><?= ucfirst($u['role_name'] ?? 'user') ?></span>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/profile/update">
                <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
                <div class="space-y-3">
                    <div><label class="block text-sm text-gray-600 mb-1">Nama</label><input type="text" name="nama" value="<?= htmlspecialchars($u['nama']) ?>" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
                    <div><label class="block text-sm text-gray-600 mb-1">Email</label><input type="email" name="email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
                    <div><label class="block text-sm text-gray-600 mb-1">No. Telepon</label><input type="text" name="no_telepon" value="<?= htmlspecialchars($u['no_telepon'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
                </div>
                <button type="submit" class="mt-4 w-full py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800"><i class="fas fa-save mr-1"></i>Update Profil</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-key mr-2 text-btn-accent"></i>Ganti Password</h3>
            <form method="POST" action="<?= BASE_URL ?>/profile/changePassword">
                <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
                <div class="space-y-3">
                    <div><label class="block text-sm text-gray-600 mb-1">Password Lama</label><input type="password" name="current_password" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
                    <div><label class="block text-sm text-gray-600 mb-1">Password Baru</label><input type="password" name="new_password" required minlength="6" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
                    <div><label class="block text-sm text-gray-600 mb-1">Konfirmasi Password Baru</label><input type="password" name="confirm_password" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
                </div>
                <button type="submit" class="mt-4 w-full py-2.5 bg-btn-accent text-white rounded-lg text-sm font-medium hover:bg-orange-700"><i class="fas fa-key mr-1"></i>Ganti Password</button>
            </form>
        </div>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
