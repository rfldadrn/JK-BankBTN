<?php require_once '../app/views/layouts/header.php'; ?>
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/laporan/nasabah" class="flex flex-wrap gap-3 items-end">
        <div><label class="block text-xs text-gray-500 mb-1">Status</label><select name="status" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="aktif" <?= $filter['status']==='aktif'?'selected':'' ?>>Aktif</option><option value="nonaktif" <?= $filter['status']==='nonaktif'?'selected':'' ?>>Nonaktif</option><option value="blacklist" <?= $filter['status']==='blacklist'?'selected':'' ?>>Blacklist</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Jenis Kelamin</label><select name="jenis_kelamin" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="L" <?= $filter['jenis_kelamin']==='L'?'selected':'' ?>>Laki-laki</option><option value="P" <?= $filter['jenis_kelamin']==='P'?'selected':'' ?>>Perempuan</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Dari</label><input type="date" name="tanggal_dari" value="<?= $filter['tanggal_dari'] ?>" class="px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <div><label class="block text-xs text-gray-500 mb-1">Sampai</label><input type="date" name="tanggal_sampai" value="<?= $filter['tanggal_sampai'] ?>" class="px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm"><i class="fas fa-filter mr-1"></i>Filter</button>
        <a href="<?= BASE_URL ?>/laporan/nasabah" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Reset</a>
        <a href="<?= BASE_URL ?>/laporan/cetakNasabah?<?= http_build_query($filter) ?>" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm"><i class="fas fa-file-pdf mr-1"></i>Cetak PDF</a>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm p-4 mb-4">
    <p class="text-sm text-gray-500">Total: <strong><?= count($data) ?></strong> nasabah</p>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">No. Nasabah</th><th class="px-3 py-2 text-left">Nama</th><th class="px-3 py-2 text-left">NIK</th><th class="px-3 py-2">JK</th><th class="px-3 py-2 text-left">Kota</th><th class="px-3 py-2">Status</th><th class="px-3 py-2 text-left">Tgl Daftar</th></tr></thead>
        <tbody class="divide-y">
            <?php foreach($data as $i => $d): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $i+1 ?></td>
                <td class="px-3 py-2 font-mono text-xs"><?= $d['no_nasabah'] ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                <td class="px-3 py-2 font-mono text-xs"><?= $d['nik'] ?></td>
                <td class="px-3 py-2 text-center"><?= $d['jenis_kelamin'] ?></td>
                <td class="px-3 py-2"><?= $d['kota_kabupaten'] ?></td>
                <td class="px-3 py-2 text-center"><?= Helper::getStatusBadge($d['status']) ?></td>
                <td class="px-3 py-2 text-xs"><?= Helper::formatTanggal($d['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
