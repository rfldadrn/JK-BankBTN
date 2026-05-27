<?php require_once '../app/views/layouts/header.php'; ?>
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/rekening" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs text-gray-500 mb-1">Pencarian</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filter['search']) ?>" placeholder="No. rekening, nama nasabah..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                <option value="">Semua</option>
                <option value="aktif" <?= $filter['status']==='aktif'?'selected':'' ?>>Aktif</option>
                <option value="beku" <?= $filter['status']==='beku'?'selected':'' ?>>Beku</option>
                <option value="tutup" <?= $filter['status']==='tutup'?'selected':'' ?>>Tutup</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Jenis</label>
            <select name="jenis_rekening" class="px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                <option value="">Semua</option>
                <option value="tabungan" <?= $filter['jenis_rekening']==='tabungan'?'selected':'' ?>>Tabungan</option>
                <option value="giro" <?= $filter['jenis_rekening']==='giro'?'selected':'' ?>>Giro</option>
                <option value="deposito" <?= $filter['jenis_rekening']==='deposito'?'selected':'' ?>>Deposito</option>
                <option value="batara" <?= $filter['jenis_rekening']==='batara'?'selected':'' ?>>Batara</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm hover:bg-blue-800"><i class="fas fa-search mr-1"></i>Cari</button>
        <a href="<?= BASE_URL ?>/rekening" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">Reset</a>
    </form>
</div>

<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500"><?= $pagination['total'] ?> data</p>
    <a href="<?= BASE_URL ?>/rekening/create" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm hover:bg-blue-800 shadow"><i class="fas fa-plus mr-1"></i>Buka Rekening</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white">
            <tr>
                <th class="px-4 py-3 text-left">No. Rekening</th>
                <th class="px-4 py-3 text-left">Nasabah</th>
                <th class="px-4 py-3 text-center">Jenis</th>
                <th class="px-4 py-3 text-right">Saldo</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if(empty($rekening)): ?>
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td></tr>
            <?php else: foreach($rekening as $r): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs"><?= $r['no_rekening'] ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($r['nama_lengkap']) ?><br><span class="text-xs text-gray-400"><?= $r['no_nasabah'] ?></span></td>
                <td class="px-4 py-3 text-center capitalize"><?= $r['jenis_rekening'] ?></td>
                <td class="px-4 py-3 text-right font-medium"><?= Helper::formatRupiah($r['saldo']) ?></td>
                <td class="px-4 py-3 text-center"><?= Helper::getStatusBadge($r['status']) ?></td>
                <td class="px-4 py-3 text-center">
                    <a href="<?= BASE_URL ?>/rekening/detail/<?= $r['id'] ?>" class="text-btn-secondary hover:text-btn-primary"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<?php if ($pagination['last_page'] > 1): ?>
<div class="flex justify-center mt-6 gap-1">
    <?php for ($p = 1; $p <= $pagination['last_page']; $p++): $qs = http_build_query(array_merge($filter, ['page'=>$p])); ?>
    <a href="<?= BASE_URL ?>/rekening?<?= $qs ?>" class="px-3 py-1 rounded text-sm <?= $p===$pagination['current_page']?'bg-btn-primary text-white':'bg-white text-gray-600 border hover:bg-gray-100' ?>"><?= $p ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php require_once '../app/views/layouts/footer.php'; ?>
