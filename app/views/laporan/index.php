<?php require_once '../app/views/layouts/header.php'; ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4"><i class="fas fa-file-alt mr-2 text-btn-primary"></i>Laporan & Cetak</h3>
        <p class="text-sm text-gray-500 mb-4">Pilih jenis laporan untuk menampilkan data dengan filter. Data bisa dicetak sebagai PDF.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="<?= BASE_URL ?>/laporan/nasabah" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-btn-primary group-hover:text-white text-btn-primary transition"><i class="fas fa-users text-xl"></i></div>
            <h4 class="font-semibold text-gray-800">Laporan Nasabah</h4>
            <p class="text-sm text-gray-500 mt-1">Daftar nasabah terdaftar berdasarkan filter status, jenis kelamin, periode.</p>
        </a>
        <a href="<?= BASE_URL ?>/laporan/transaksi" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-btn-accent group-hover:text-white text-btn-accent transition"><i class="fas fa-exchange-alt text-xl"></i></div>
            <h4 class="font-semibold text-gray-800">Laporan Transaksi</h4>
            <p class="text-sm text-gray-500 mt-1">Riwayat transaksi per periode, jenis, dan rekening.</p>
        </a>
        <a href="<?= BASE_URL ?>/laporan/kredit" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white text-purple-600 transition"><i class="fas fa-file-invoice-dollar text-xl"></i></div>
            <h4 class="font-semibold text-gray-800">Laporan Kredit</h4>
            <p class="text-sm text-gray-500 mt-1">Outstanding kredit, kolektibilitas, dan jatuh tempo.</p>
        </a>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
