<?php require_once '../app/views/layouts/header.php'; ?>
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Manajemen User</h3>
    <a href="<?= BASE_URL ?>/users/create" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm shadow"><i class="fas fa-plus mr-1"></i>Tambah User</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white"><tr><th class="px-4 py-3 text-left">Username</th><th class="px-4 py-3 text-left">Nama Lengkap</th><th class="px-4 py-3 text-left">Email</th><th class="px-4 py-3 text-center">Role</th><th class="px-4 py-3 text-center">Status</th><th class="px-4 py-3 text-center">Aksi</th></tr></thead>
        <tbody class="divide-y">
            <?php foreach($users as $u): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium"><?= htmlspecialchars($u['username']) ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($u['nama']) ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><?= $u['role_name'] ?? '-' ?></span></td>
                <td class="px-4 py-3 text-center"><?= Helper::getStatusBadge($u['status']) ?></td>
                <td class="px-4 py-3 text-center">
                    <a href="<?= BASE_URL ?>/users/edit/<?= $u['id'] ?>" class="text-yellow-500 hover:text-yellow-700"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
