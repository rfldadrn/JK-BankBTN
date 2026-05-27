<?php require_once '../app/views/layouts/header.php'; ?>
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="<?= BASE_URL ?>/users/store">
        <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
        <div class="space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Username *</label><input type="text" name="username" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="nama" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label><input type="email" name="email" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Password *</label><input type="password" name="password" required minlength="6" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label><input type="password" name="password_confirm" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Role *</label><select name="role_id" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"><option value="">-- Pilih Role --</option><?php foreach($roles as $r): ?><option value="<?= $r['id'] ?>"><?= ucfirst($r['name']) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800"><i class="fas fa-save mr-1"></i>Simpan</button>
            <a href="<?= BASE_URL ?>/users" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
