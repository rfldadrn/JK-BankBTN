<?php require_once '../app/views/layouts/header.php'; ?>
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/laporan/kredit" class="flex flex-wrap gap-3 items-end">
        <div><label class="block text-xs text-gray-500 mb-1">Jenis</label><select name="jenis_kredit" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="KPR" <?= $filter['jenis_kredit']==='KPR'?'selected':'' ?>>KPR</option><option value="KKB" <?= $filter['jenis_kredit']==='KKB'?'selected':'' ?>>KKB</option><option value="KMK" <?= $filter['jenis_kredit']==='KMK'?'selected':'' ?>>KMK</option><option value="KTA" <?= $filter['jenis_kredit']==='KTA'?'selected':'' ?>>KTA</option><option value="KUR" <?= $filter['jenis_kredit']==='KUR'?'selected':'' ?>>KUR</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Kolektibilitas</label><select name="kolektibilitas" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="lancar" <?= $filter['kolektibilitas']==='lancar'?'selected':'' ?>>Lancar</option><option value="perhatian_khusus" <?= $filter['kolektibilitas']==='perhatian_khusus'?'selected':'' ?>>Perhatian Khusus</option><option value="kurang_lancar" <?= $filter['kolektibilitas']==='kurang_lancar'?'selected':'' ?>>Kurang Lancar</option><option value="diragukan" <?= $filter['kolektibilitas']==='diragukan'?'selected':'' ?>>Diragukan</option><option value="macet" <?= $filter['kolektibilitas']==='macet'?'selected':'' ?>>Macet</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Status</label><select name="status" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="aktif" <?= $filter['status']==='aktif'?'selected':'' ?>>Aktif</option><option value="lunas" <?= $filter['status']==='lunas'?'selected':'' ?>>Lunas</option><option value="macet" <?= $filter['status']==='macet'?'selected':'' ?>>Macet</option></select></div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm"><i class="fas fa-filter mr-1"></i>Filter</button>
        <a href="<?= BASE_URL ?>/laporan/kredit" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Reset</a>
        <a href="<?= BASE_URL ?>/laporan/cetakKredit?<?= http_build_query($filter) ?>" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm"><i class="fas fa-file-pdf mr-1"></i>Cetak PDF</a>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm p-4 mb-4">
    <p class="text-sm text-gray-500">Total: <strong><?= count($data) ?></strong> kredit • Outstanding: <strong class="text-btn-accent"><?= Helper::formatRupiah(array_sum(array_column($data, 'outstanding'))) ?></strong></p>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white"><tr><th class="px-3 py-2">No</th><th class="px-3 py-2 text-left">No. Kredit</th><th class="px-3 py-2 text-left">Nasabah</th><th class="px-3 py-2">Jenis</th><th class="px-3 py-2 text-right">Plafon</th><th class="px-3 py-2 text-right">Outstanding</th><th class="px-3 py-2">Kolektibilitas</th><th class="px-3 py-2 text-left">Jatuh Tempo</th></tr></thead>
        <tbody class="divide-y">
            <?php foreach($data as $i => $d): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $i+1 ?></td>
                <td class="px-3 py-2 font-mono text-xs"><?= $d['no_kredit'] ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                <td class="px-3 py-2 text-center"><?= $d['jenis_kredit'] ?></td>
                <td class="px-3 py-2 text-right"><?= Helper::formatRupiah($d['plafon']) ?></td>
                <td class="px-3 py-2 text-right font-medium"><?= Helper::formatRupiah($d['outstanding']) ?></td>
                <td class="px-3 py-2 text-center"><?= Helper::getStatusBadge($d['kolektibilitas']) ?></td>
                <td class="px-3 py-2 text-xs"><?= Helper::formatTanggal($d['tanggal_jatuh_tempo']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
