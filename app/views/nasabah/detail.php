<?php require_once '../app/views/layouts/header.php'; ?>

<div class="max-w-5xl mx-auto">
    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex gap-4">
                <div class="w-16 h-16 bg-btn-primary rounded-full flex items-center justify-center text-white text-xl font-bold">
                    <?= strtoupper(substr($nasabah['nama_lengkap'], 0, 2)) ?>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($nasabah['nama_lengkap']) ?></h2>
                    <p class="text-sm text-gray-500"><?= $nasabah['no_nasabah'] ?> • NIK: <?= $nasabah['nik'] ?></p>
                    <div class="mt-2 flex flex-wrap gap-2 items-center">
                        <?= Helper::getStatusBadge($nasabah['status']) ?>
                        <?= Helper::getNasabahSegmentBadge($nasabah['segment_key'] ?? 'mass', $nasabah['segment_label'] ?? 'Mass Segment') ?>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <?php if (in_array(Auth::role(), ['admin', 'cs'])): ?>
                <a href="<?= BASE_URL ?>/nasabah/edit/<?= $nasabah['id'] ?>" class="px-3 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600"><i class="fas fa-edit mr-1"></i>Edit</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/nasabah" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Pribadi -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-user mr-2 text-btn-primary"></i>Data Pribadi</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Tempat/Tanggal Lahir</span><p class="font-medium"><?= htmlspecialchars($nasabah['tempat_lahir'] ?? '-') ?>, <?= Helper::formatTanggal($nasabah['tanggal_lahir']) ?></p></div>
                    <div><span class="text-gray-500">Jenis Kelamin</span><p class="font-medium"><?= $nasabah['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></p></div>
                    <div><span class="text-gray-500">Agama</span><p class="font-medium"><?= $nasabah['agama'] ?: '-' ?></p></div>
                    <div><span class="text-gray-500">Status Perkawinan</span><p class="font-medium capitalize"><?= $nasabah['status_perkawinan'] ?: '-' ?></p></div>
                    <div><span class="text-gray-500">Pekerjaan</span><p class="font-medium"><?= $nasabah['pekerjaan'] ?: '-' ?></p></div>
                    <div><span class="text-gray-500">Penghasilan</span><p class="font-medium"><?= $nasabah['penghasilan_bulanan'] ? Helper::formatRupiah($nasabah['penghasilan_bulanan']) : '-' ?></p></div>
                    <div><span class="text-gray-500">No. Telepon</span><p class="font-medium"><?= $nasabah['no_telepon'] ?></p></div>
                    <div><span class="text-gray-500">Email</span><p class="font-medium"><?= $nasabah['email'] ?: '-' ?></p></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-map-marker-alt mr-2 text-btn-primary"></i>Alamat</h3>
                <p class="text-sm"><?= htmlspecialchars($nasabah['alamat']) ?></p>
                <p class="text-sm text-gray-500 mt-1">RT/RW: <?= $nasabah['rt_rw'] ?: '-' ?>, Kel. <?= $nasabah['kelurahan'] ?: '-' ?>, Kec. <?= $nasabah['kecamatan'] ?: '-' ?></p>
                <p class="text-sm text-gray-500"><?= $nasabah['kota_kabupaten'] ?>, <?= $nasabah['provinsi'] ?> <?= $nasabah['kode_pos'] ?></p>
            </div>

            <!-- Rekening -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-university mr-2 text-green-600"></i>Rekening (<?= count($rekening) ?>)</h3>
                <?php if (empty($rekening)): ?>
                <p class="text-sm text-gray-400">Belum ada rekening.</p>
                <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($rekening as $rek): ?>
                    <div class="p-3 border rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-mono text-sm font-medium"><?= $rek['no_rekening'] ?></p>
                            <p class="text-xs text-gray-500 capitalize"><?= $rek['jenis_rekening'] ?> • <?= $rek['nama_produk'] ?: '-' ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-sm"><?= Helper::formatRupiah($rek['saldo']) ?></p>
                            <?= Helper::getStatusBadge($rek['status']) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-layer-group mr-2 text-indigo-600"></i>Segmentasi Nasabah</h3>
                <div class="space-y-3 text-sm mb-5">
                    <div>
                        <span class="text-gray-500">Saldo / AUM</span>
                        <p class="font-semibold text-base"><?= Helper::formatRupiah($nasabah['total_aum'] ?? 0) ?></p>
                    </div>
                    <div>
                        <span class="text-gray-500">Klasifikasi</span>
                        <p class="font-medium mt-1"><?= htmlspecialchars($nasabah['segment_label'] ?? 'Mass Segment') ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($nasabah['segment_description'] ?? '-') ?></p>
                    </div>
                </div>

                <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-info-circle mr-2 text-btn-primary"></i>Info Sistem</h3>
                <div class="space-y-3 text-sm">
                    <div><span class="text-gray-500">Terdaftar</span><p class="font-medium"><?= Helper::formatTanggal($nasabah['created_at']) ?></p></div>
                    <div><span class="text-gray-500">Terakhir Update</span><p class="font-medium"><?= Helper::formatTanggal($nasabah['updated_at'] ?? $nasabah['created_at']) ?></p></div>
                </div>
            </div>

            <!-- Kredit -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-file-invoice-dollar mr-2 text-purple-600"></i>Kredit (<?= count($kredit) ?>)</h3>
                <?php if (empty($kredit)): ?>
                <p class="text-sm text-gray-400">Tidak ada kredit.</p>
                <?php else: foreach ($kredit as $kr): ?>
                <div class="p-3 border rounded-lg mb-2">
                    <p class="font-mono text-xs"><?= $kr['no_kredit'] ?></p>
                    <p class="text-sm font-medium"><?= $kr['jenis_kredit'] ?></p>
                    <p class="text-xs text-gray-500"><?= Helper::formatRupiah($kr['plafon']) ?></p>
                    <?= Helper::getStatusBadge($kr['kolektibilitas']) ?>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
