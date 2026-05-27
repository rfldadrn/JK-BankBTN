<?php require_once '../app/views/layouts/header.php'; $r = $rekening; ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold font-mono"><?= $r['no_rekening'] ?></h2>
                <p class="text-sm text-gray-500 capitalize"><?= $r['jenis_rekening'] ?> • <?= $r['nama_produk'] ?: '-' ?></p>
                <p class="text-sm mt-1">Nasabah: <strong><?= htmlspecialchars($r['nama_lengkap']) ?></strong> (<?= $r['no_nasabah'] ?>)</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-btn-primary"><?= Helper::formatRupiah($r['saldo']) ?></p>
                <?= Helper::getStatusBadge($r['status']) ?>
            </div>
        </div>
        <?php if(in_array(Auth::role(), ['admin','backoffice'])): ?>
        <form method="POST" action="<?= BASE_URL ?>/rekening/updateStatus/<?= $r['id'] ?>" class="mt-4 pt-4 border-t flex items-center gap-3">
            <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
            <select name="status" class="px-3 py-2 border rounded-lg text-sm">
                <option value="aktif" <?= $r['status']==='aktif'?'selected':'' ?>>Aktif</option>
                <option value="beku" <?= $r['status']==='beku'?'selected':'' ?>>Beku</option>
                <option value="tutup" <?= $r['status']==='tutup'?'selected':'' ?>>Tutup</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600">Update Status</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Riwayat Transaksi Terakhir</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left">Tanggal</th><th class="px-3 py-2 text-left">No. Transaksi</th><th class="px-3 py-2 text-left">Jenis</th><th class="px-3 py-2 text-right">Jumlah</th><th class="px-3 py-2 text-right">Saldo</th></tr></thead>
            <tbody class="divide-y">
                <?php foreach($transaksi as $t): ?>
                <tr>
                    <td class="px-3 py-2 text-xs"><?= Helper::formatTanggal($t['tanggal_transaksi']) ?></td>
                    <td class="px-3 py-2 font-mono text-xs"><?= $t['no_transaksi'] ?></td>
                    <td class="px-3 py-2 capitalize"><?= str_replace('_',' ',$t['jenis_transaksi']) ?></td>
                    <td class="px-3 py-2 text-right <?= in_array($t['jenis_transaksi'],['setoran','transfer_masuk'])?'text-green-600':'text-red-600' ?>"><?= Helper::formatRupiah($t['jumlah']) ?></td>
                    <td class="px-3 py-2 text-right"><?= Helper::formatRupiah($t['saldo_sesudah']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
