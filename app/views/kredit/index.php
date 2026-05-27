<?php require_once '../app/views/layouts/header.php'; ?>
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/kredit" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]"><label class="block text-xs text-gray-500 mb-1">Pencarian</label><input type="text" name="search" value="<?= htmlspecialchars($filter['search']) ?>" placeholder="No. kredit, nama, jenis..." class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <div><label class="block text-xs text-gray-500 mb-1">Status</label><select name="status" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="aktif" <?= $filter['status']==='aktif'?'selected':'' ?>>Aktif</option><option value="lunas" <?= $filter['status']==='lunas'?'selected':'' ?>>Lunas</option><option value="macet" <?= $filter['status']==='macet'?'selected':'' ?>>Macet</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Kolektibilitas</label><select name="kolektibilitas" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="lancar" <?= $filter['kolektibilitas']==='lancar'?'selected':'' ?>>Lancar</option><option value="perhatian_khusus" <?= $filter['kolektibilitas']==='perhatian_khusus'?'selected':'' ?>>Perhatian Khusus</option><option value="kurang_lancar" <?= $filter['kolektibilitas']==='kurang_lancar'?'selected':'' ?>>Kurang Lancar</option><option value="diragukan" <?= $filter['kolektibilitas']==='diragukan'?'selected':'' ?>>Diragukan</option><option value="macet" <?= $filter['kolektibilitas']==='macet'?'selected':'' ?>>Macet</option></select></div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm"><i class="fas fa-search mr-1"></i>Cari</button>
        <a href="<?= BASE_URL ?>/kredit" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Reset</a>
    </form>
</div>
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500"><?= $pagination['total'] ?> data</p>
    <?php if(in_array(Auth::role(),['admin','backoffice'])): ?>
    <a href="<?= BASE_URL ?>/kredit/create" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm shadow"><i class="fas fa-plus mr-1"></i>Input Kredit</a>
    <?php endif; ?>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white"><tr><th class="px-4 py-3 text-left">No. Kredit</th><th class="px-4 py-3 text-left">Nasabah</th><th class="px-4 py-3 text-center">Jenis</th><th class="px-4 py-3 text-right">Plafon</th><th class="px-4 py-3 text-right">Outstanding</th><th class="px-4 py-3 text-center">Kolektibilitas</th><th class="px-4 py-3 text-center">Aksi</th></tr></thead>
        <tbody class="divide-y">
            <?php if(empty($kredit)): ?><tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td></tr>
            <?php else: foreach($kredit as $k): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs"><?= $k['no_kredit'] ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($k['nama_lengkap']) ?></td>
                <td class="px-4 py-3 text-center"><?= $k['jenis_kredit'] ?></td>
                <td class="px-4 py-3 text-right"><?= Helper::formatRupiah($k['plafon']) ?></td>
                <td class="px-4 py-3 text-right font-medium"><?= Helper::formatRupiah($k['outstanding']) ?></td>
                <td class="px-4 py-3 text-center"><?= Helper::getStatusBadge($k['kolektibilitas']) ?></td>
                <td class="px-4 py-3 text-center"><a href="<?= BASE_URL ?>/kredit/detail/<?= $k['id'] ?>" class="text-btn-secondary"><i class="fas fa-eye"></i></a></td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php if ($pagination['last_page'] > 1): ?><div class="flex justify-center mt-6 gap-1"><?php for($p=1;$p<=$pagination['last_page'];$p++): $qs=http_build_query(array_merge($filter,['page'=>$p])); ?><a href="<?= BASE_URL ?>/kredit?<?= $qs ?>" class="px-3 py-1 rounded text-sm <?= $p===$pagination['current_page']?'bg-btn-primary text-white':'bg-white text-gray-600 border' ?>"><?= $p ?></a><?php endfor; ?></div><?php endif; ?>
<?php require_once '../app/views/layouts/footer.php'; ?>
