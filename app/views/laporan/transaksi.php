<?php require_once '../app/views/layouts/header.php'; ?>
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/laporan/transaksi" class="flex flex-wrap gap-3 items-end">
        <div><label class="block text-xs text-gray-500 mb-1">Jenis</label><select name="jenis_transaksi" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="setoran" <?= $filter['jenis_transaksi']==='setoran'?'selected':'' ?>>Setoran</option><option value="penarikan" <?= $filter['jenis_transaksi']==='penarikan'?'selected':'' ?>>Penarikan</option><option value="transfer_keluar" <?= $filter['jenis_transaksi']==='transfer_keluar'?'selected':'' ?>>Transfer Keluar</option><option value="transfer_masuk" <?= $filter['jenis_transaksi']==='transfer_masuk'?'selected':'' ?>>Transfer Masuk</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Dari</label><input type="date" name="tanggal_dari" value="<?= $filter['tanggal_dari'] ?>" class="px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <div><label class="block text-xs text-gray-500 mb-1">Sampai</label><input type="date" name="tanggal_sampai" value="<?= $filter['tanggal_sampai'] ?>" class="px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm"><i class="fas fa-filter mr-1"></i>Filter</button>
        <a href="<?= BASE_URL ?>/laporan/transaksi" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Reset</a>
        <a href="<?= BASE_URL ?>/laporan/cetakTransaksi?<?= http_build_query($filter) ?>" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm"><i class="fas fa-file-pdf mr-1"></i>Cetak PDF</a>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-6">
    <p class="text-sm text-gray-500">Total: <strong><?= count($data) ?></strong> transaksi</p>
    <p class="text-sm text-gray-500">Total Debit: <strong class="text-red-600"><?= Helper::formatRupiah(array_sum(array_map(fn($d)=> in_array($d['jenis_transaksi'],['penarikan','transfer_keluar'])?$d['jumlah']:0, $data))) ?></strong></p>
    <p class="text-sm text-gray-500">Total Kredit: <strong class="text-green-600"><?= Helper::formatRupiah(array_sum(array_map(fn($d)=> in_array($d['jenis_transaksi'],['setoran','transfer_masuk'])?$d['jumlah']:0, $data))) ?></strong></p>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white"><tr><th class="px-3 py-2">No</th><th class="px-3 py-2 text-left">No. Transaksi</th><th class="px-3 py-2 text-left">Tanggal</th><th class="px-3 py-2 text-left">Nasabah</th><th class="px-3 py-2">Jenis</th><th class="px-3 py-2 text-right">Jumlah</th><th class="px-3 py-2">Status</th></tr></thead>
        <tbody class="divide-y">
            <?php foreach($data as $i => $d): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $i+1 ?></td>
                <td class="px-3 py-2 font-mono text-xs"><?= $d['no_transaksi'] ?></td>
                <td class="px-3 py-2 text-xs"><?= Helper::formatTanggal($d['tanggal_transaksi']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                <td class="px-3 py-2 text-center capitalize text-xs"><?= str_replace('_',' ',$d['jenis_transaksi']) ?></td>
                <td class="px-3 py-2 text-right font-medium <?= in_array($d['jenis_transaksi'],['setoran','transfer_masuk'])?'text-green-600':'text-red-600' ?>"><?= Helper::formatRupiah($d['jumlah']) ?></td>
                <td class="px-3 py-2 text-center"><?= Helper::getStatusBadge($d['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
