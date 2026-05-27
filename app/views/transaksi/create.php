<?php require_once '../app/views/layouts/header.php'; ?>
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="<?= BASE_URL ?>/transaksi/store">
        <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rekening Sumber *</label>
                <select name="rekening_id" id="rekening_id" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none">
                    <option value="">-- Pilih Rekening --</option>
                    <?php foreach($rekening_list as $r): ?>
                    <option value="<?= $r['id'] ?>" data-saldo="<?= $r['saldo'] ?>"><?= $r['no_rekening'] ?> - <?= htmlspecialchars($r['nama_lengkap']) ?> (Saldo: <?= Helper::formatRupiah($r['saldo']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi *</label>
                <select name="jenis_transaksi" id="jenis_transaksi" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none" onchange="toggleTarget()">
                    <option value="setoran">Setoran</option>
                    <option value="penarikan">Penarikan</option>
                    <option value="transfer_keluar">Transfer</option>
                </select>
            </div>
            <div id="target_wrapper" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rekening Tujuan</label>
                <select name="rekening_tujuan_id" class="w-full px-3 py-2 border rounded-lg text-sm outline-none">
                    <option value="">-- Pilih Tujuan --</option>
                    <?php foreach($rekening_list as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= $r['no_rekening'] ?> - <?= htmlspecialchars($r['nama_lengkap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                <input type="number" name="jumlah" required min="1" class="w-full px-3 py-2 border rounded-lg text-sm outline-none" placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800"><i class="fas fa-save mr-1"></i>Simpan Transaksi</button>
            <a href="<?= BASE_URL ?>/transaksi" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm">Batal</a>
        </div>
    </form>
</div>
<script>
function toggleTarget() {
    document.getElementById('target_wrapper').classList.toggle('hidden', document.getElementById('jenis_transaksi').value !== 'transfer_keluar');
}
</script>
<?php require_once '../app/views/layouts/footer.php'; ?>
