<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/nasabah" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs text-gray-500 mb-1">Pencarian</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filter['search']) ?>" placeholder="Cari nama, NIK, no. nasabah, telepon..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary focus:border-transparent outline-none">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                <option value="">Semua Status</option>
                <option value="aktif" <?= $filter['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= $filter['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                <option value="blacklist" <?= $filter['status'] === 'blacklist' ? 'selected' : '' ?>>Blacklist</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                <option value="">Semua</option>
                <option value="L" <?= $filter['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= $filter['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm hover:bg-blue-800">
            <i class="fas fa-search mr-1"></i> Cari
        </button>
        <a href="<?= BASE_URL ?>/nasabah" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">Reset</a>
    </form>
</div>

<!-- Action Bar -->
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500">Menampilkan <?= $pagination['from'] ?>-<?= $pagination['to'] ?> dari <?= $pagination['total'] ?> data</p>
    <?php if (in_array(Auth::role(), ['admin', 'cs'])): ?>
    <a href="<?= BASE_URL ?>/nasabah/create" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm hover:bg-blue-800 shadow">
        <i class="fas fa-plus mr-1"></i> Tambah Nasabah
    </a>
    <?php endif; ?>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-btn-primary text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No. Nasabah</th>
                    <th class="px-4 py-3 text-left">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left">NIK</th>
                    <th class="px-4 py-3 text-left">No. Telepon</th>
                    <th class="px-4 py-3 text-center">JK</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($nasabah)): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data nasabah.</td></tr>
                <?php else: foreach ($nasabah as $i => $ns): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs"><?= $ns['no_nasabah'] ?></td>
                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($ns['nama_lengkap']) ?></td>
                    <td class="px-4 py-3 font-mono text-xs"><?= $ns['nik'] ?></td>
                    <td class="px-4 py-3"><?= $ns['no_telepon'] ?></td>
                    <td class="px-4 py-3 text-center"><?= $ns['jenis_kelamin'] === 'L' ? '♂' : '♀' ?></td>
                    <td class="px-4 py-3 text-center"><?= Helper::getStatusBadge($ns['status']) ?></td>
                    <td class="px-4 py-3 text-center">
                        <a href="<?= BASE_URL ?>/nasabah/detail/<?= $ns['id'] ?>" class="text-btn-secondary hover:text-btn-primary" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if (in_array(Auth::role(), ['admin', 'cs'])): ?>
                        <a href="<?= BASE_URL ?>/nasabah/edit/<?= $ns['id'] ?>" class="ml-2 text-yellow-500 hover:text-yellow-700" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($pagination['last_page'] > 1): ?>
<div class="flex justify-center mt-6 gap-1">
    <?php for ($p = 1; $p <= $pagination['last_page']; $p++): 
        $params = array_merge($filter, ['page' => $p]);
        $qs = http_build_query(array_filter($params));
    ?>
    <a href="<?= BASE_URL ?>/nasabah?<?= $qs ?>" 
       class="px-3 py-1 rounded text-sm <?= $p === $pagination['current_page'] ? 'bg-btn-primary text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border' ?>">
        <?= $p ?>
    </a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
