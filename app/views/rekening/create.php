<?php require_once '../app/views/layouts/header.php'; ?>
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="<?= BASE_URL ?>/rekening/store">
        <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nasabah *</label>
                <select name="nasabah_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                    <option value="">-- Pilih Nasabah --</option>
                    <?php foreach($nasabah_list as $n): ?>
                    <option value="<?= $n['id'] ?>"><?= htmlspecialchars($n['nama_lengkap']) ?> (<?= $n['no_nasabah'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Rekening *</label>
                <select name="jenis_rekening" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                    <option value="tabungan">Tabungan</option>
                    <option value="giro">Giro</option>
                    <option value="deposito">Deposito</option>
                    <option value="batara">BTN Batara</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none" placeholder="cth: BTN Batara, BTN Prima">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Setoran Awal (Rp) *</label>
                <input type="number" name="saldo" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none" placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none"></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800"><i class="fas fa-save mr-1"></i>Buka Rekening</button>
            <a href="<?= BASE_URL ?>/rekening" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
