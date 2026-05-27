<?php require_once '../app/views/layouts/header.php'; $k = $kredit; ?>
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-lg font-bold font-mono"><?= $k['no_kredit'] ?></h2>
                <p class="text-sm text-gray-500"><?= $k['jenis_kredit'] ?> • <?= htmlspecialchars($k['nama_lengkap']) ?> (<?= $k['no_nasabah'] ?>)</p>
            </div>
            <div class="flex gap-2"><?= Helper::getStatusBadge($k['status']) ?> <?= Helper::getStatusBadge($k['kolektibilitas']) ?></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 pt-4 border-t text-sm">
            <div><span class="text-gray-500">Plafon</span><p class="font-bold"><?= Helper::formatRupiah($k['plafon']) ?></p></div>
            <div><span class="text-gray-500">Outstanding</span><p class="font-bold text-btn-accent"><?= Helper::formatRupiah($k['outstanding']) ?></p></div>
            <div><span class="text-gray-500">Bunga</span><p class="font-bold"><?= $k['suku_bunga'] ?>% /tahun</p></div>
            <div><span class="text-gray-500">Tenor</span><p class="font-bold"><?= $k['tenor_bulan'] ?> bulan</p></div>
            <div><span class="text-gray-500">Angsuran/bulan</span><p class="font-bold"><?= Helper::formatRupiah($k['angsuran_per_bulan']) ?></p></div>
            <div><span class="text-gray-500">Tanggal Mulai</span><p><?= Helper::formatTanggal($k['tanggal_mulai']) ?></p></div>
            <div><span class="text-gray-500">Jatuh Tempo</span><p><?= Helper::formatTanggal($k['tanggal_jatuh_tempo']) ?></p></div>
            <div><span class="text-gray-500">Jaminan</span><p><?= htmlspecialchars($k['jaminan'] ?: '-') ?></p></div>
        </div>
    </div>

    <!-- Jadwal Angsuran -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Jadwal Angsuran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr><th class="px-3 py-2">Ke-</th><th class="px-3 py-2">Jatuh Tempo</th><th class="px-3 py-2 text-right">Pokok</th><th class="px-3 py-2 text-right">Bunga</th><th class="px-3 py-2 text-right">Total</th><th class="px-3 py-2 text-center">Status</th><th class="px-3 py-2">Bayar</th><th class="px-3 py-2">Aksi</th></tr></thead>
                <tbody class="divide-y">
                    <?php foreach($angsuran as $a): ?>
                    <tr>
                        <td class="px-3 py-2 text-center"><?= $a['periode_ke'] ?></td>
                        <td class="px-3 py-2"><?= Helper::formatTanggal($a['tanggal_jatuh_tempo']) ?></td>
                        <td class="px-3 py-2 text-right"><?= Helper::formatRupiah($a['pokok']) ?></td>
                        <td class="px-3 py-2 text-right"><?= Helper::formatRupiah($a['bunga']) ?></td>
                        <td class="px-3 py-2 text-right font-medium"><?= Helper::formatRupiah($a['total_angsuran']) ?></td>
                        <td class="px-3 py-2 text-center"><?= Helper::getStatusBadge($a['status']) ?></td>
                        <td class="px-3 py-2 text-xs"><?= $a['tanggal_bayar'] ? Helper::formatTanggal($a['tanggal_bayar']) : '-' ?></td>
                        <td class="px-3 py-2">
                            <?php if($a['status'] === 'belum_bayar' && in_array(Auth::role(), ['admin','backoffice'])): ?>
                            <form method="POST" action="<?= BASE_URL ?>/kredit/bayarAngsuran/<?= $k['id'] ?>" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
                                <input type="hidden" name="angsuran_id" value="<?= $a['id'] ?>">
                                <button type="submit" class="text-xs text-green-600 hover:underline" onclick="return confirm('Konfirmasi pembayaran?')">Bayar</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
