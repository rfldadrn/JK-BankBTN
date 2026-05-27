<?php require_once '../app/views/layouts/header.php'; $ns = $nasabah; ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="<?= BASE_URL ?>/nasabah/update/<?= $ns['id'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= Helper::csrfToken() ?>">

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b"><i class="fas fa-user mr-2"></i>Data Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK *</label>
                    <input type="text" name="nik" value="<?= htmlspecialchars($ns['nik']) ?>" maxlength="16" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($ns['nama_lengkap']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($ns['tempat_lahir'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" value="<?= $ns['tanggal_lahir'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                        <option value="L" <?= $ns['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $ns['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                    <select name="agama" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $a): ?>
                        <option value="<?= $a ?>" <?= ($ns['agama'] ?? '') === $a ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                    <select name="status_perkawinan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                        <option value="lajang" <?= $ns['status_perkawinan'] === 'lajang' ? 'selected' : '' ?>>Lajang</option>
                        <option value="menikah" <?= $ns['status_perkawinan'] === 'menikah' ? 'selected' : '' ?>>Menikah</option>
                        <option value="cerai" <?= $ns['status_perkawinan'] === 'cerai' ? 'selected' : '' ?>>Cerai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="<?= htmlspecialchars($ns['pekerjaan'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan</label>
                    <input type="number" name="penghasilan_bulanan" value="<?= $ns['penghasilan_bulanan'] ?? '' ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                    <input type="text" name="no_telepon" value="<?= htmlspecialchars($ns['no_telepon']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($ns['email'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none">
                </div>
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b"><i class="fas fa-map-marker-alt mr-2"></i>Alamat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                    <textarea name="alamat" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"><?= htmlspecialchars($ns['alamat']) ?></textarea>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">RT/RW</label><input type="text" name="rt_rw" value="<?= htmlspecialchars($ns['rt_rw'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label><input type="text" name="kelurahan" value="<?= htmlspecialchars($ns['kelurahan'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label><input type="text" name="kecamatan" value="<?= htmlspecialchars($ns['kecamatan'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten *</label><input type="text" name="kota_kabupaten" value="<?= htmlspecialchars($ns['kota_kabupaten']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Provinsi *</label><input type="text" name="provinsi" value="<?= htmlspecialchars($ns['provinsi']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label><input type="text" name="kode_pos" value="<?= htmlspecialchars($ns['kode_pos'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-btn-primary outline-none"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload KTP Baru (opsional)</label>
                <input type="file" name="file_ktp" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="px-6 py-2.5 bg-btn-primary text-white rounded-lg text-sm font-medium hover:bg-blue-800 shadow"><i class="fas fa-save mr-1"></i> Update</button>
                <a href="<?= BASE_URL ?>/nasabah/detail/<?= $ns['id'] ?>" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
