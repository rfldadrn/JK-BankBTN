<?php require_once '../app/views/layouts/header.php'; ?>
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="<?= BASE_URL ?>/kredit/store">
        <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Nasabah *</label><select name="nasabah_id" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"><option value="">-- Pilih --</option><?php foreach($nasabah_list as $n): ?><option value="<?= $n['id'] ?>"><?= htmlspecialchars($n['nama_lengkap']) ?> (<?= $n['no_nasabah'] ?>)</option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kredit *</label><select name="jenis_kredit" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"><option value="KPR">KPR</option><option value="KKB">KKB</option><option value="KMK">KMK</option><option value="KTA">KTA</option><option value="KUR">KUR</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Plafon (Rp) *</label><input type="number" name="plafon" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Suku Bunga (% /tahun) *</label><input type="number" name="suku_bunga" step="0.01" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Tenor (bulan) *</label><input type="number" name="tenor_bulan" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label><input type="date" name="tanggal_mulai" required class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Jaminan</label><input type="text" name="jaminan" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Kredit</label><textarea name="tujuan_kredit" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></textarea></div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800"><i class="fas fa-save mr-1"></i>Simpan</button>
            <a href="<?= BASE_URL ?>/kredit" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
