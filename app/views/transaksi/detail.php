<?php require_once '../app/views/layouts/header.php'; $t = $transaksi; ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-center mb-6 pb-4 border-b">
            <h2 class="text-lg font-bold text-btn-primary">BUKTI TRANSAKSI</h2>
            <p class="text-xs text-gray-500">Bank BTN KC Pekanbaru</p>
        </div>
        <table class="w-full text-sm">
            <tr><td class="py-2 text-gray-500 w-40">No. Transaksi</td><td class="py-2 font-mono font-medium"><?= $t['no_transaksi'] ?></td></tr>
            <tr><td class="py-2 text-gray-500">Tanggal</td><td class="py-2"><?= Helper::formatTanggal($t['tanggal_transaksi']) ?> <?= date('H:i', strtotime($t['tanggal_transaksi'])) ?></td></tr>
            <tr><td class="py-2 text-gray-500">Jenis</td><td class="py-2 capitalize font-medium"><?= str_replace('_',' ',$t['jenis_transaksi']) ?></td></tr>
            <tr><td class="py-2 text-gray-500">Nasabah</td><td class="py-2"><?= htmlspecialchars($t['nama_lengkap']) ?> (<?= $t['no_nasabah'] ?>)</td></tr>
            <tr><td class="py-2 text-gray-500">No. Rekening</td><td class="py-2 font-mono"><?= $t['no_rekening'] ?></td></tr>
            <tr><td class="py-2 text-gray-500">Jumlah</td><td class="py-2 text-xl font-bold text-btn-primary"><?= Helper::formatRupiah($t['jumlah']) ?></td></tr>
            <tr><td class="py-2 text-gray-500">Saldo Sebelum</td><td class="py-2"><?= Helper::formatRupiah($t['saldo_sebelum']) ?></td></tr>
            <tr><td class="py-2 text-gray-500">Saldo Sesudah</td><td class="py-2 font-medium"><?= Helper::formatRupiah($t['saldo_sesudah']) ?></td></tr>
            <tr><td class="py-2 text-gray-500">Keterangan</td><td class="py-2"><?= htmlspecialchars($t['keterangan'] ?: '-') ?></td></tr>
            <tr><td class="py-2 text-gray-500">Teller</td><td class="py-2"><?= htmlspecialchars($t['nama_teller']) ?></td></tr>
            <tr><td class="py-2 text-gray-500">Status</td><td class="py-2"><?= Helper::getStatusBadge($t['status']) ?></td></tr>
        </table>
        <div class="mt-6 pt-4 border-t flex gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm no-print"><i class="fas fa-print mr-1"></i>Cetak</button>
            <a href="<?= BASE_URL ?>/transaksi" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm no-print">Kembali</a>
        </div>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
