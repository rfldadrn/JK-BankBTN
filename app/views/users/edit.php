<?php require_once '../app/views/layouts/header.php'; $u = $editUser; ?>
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="<?= BASE_URL ?>/users/update/<?= $u['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
        <div class="space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Username *</label><input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="nama" value="<?= htmlspecialchars($u['nama']) ?>" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Email *</label><input type="email" name="email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (kosongkan jika tidak diubah)</label><input type="password" name="password" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Role *</label><select name="role_id" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"><?php foreach($roles as $r): ?><option value="<?= $r['id'] ?>" <?= ($u['role_id'] ?? '') == $r['id'] ? 'selected' : '' ?>><?= ucfirst($r['name']) ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"><option value="aktif" <?= $u['status']==='aktif'?'selected':'' ?>>Aktif</option><option value="nonaktif" <?= $u['status']==='nonaktif'?'selected':'' ?>>Nonaktif</option></select></div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800"><i class="fas fa-save mr-1"></i>Update</button>
            <a href="<?= BASE_URL ?>/users" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
