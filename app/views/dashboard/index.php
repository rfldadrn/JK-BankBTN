<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-btn-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Nasabah</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_nasabah) ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-btn-primary text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Rekening Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_rekening) ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                <i class="fas fa-university text-green-500 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-btn-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Transaksi Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_transaksi) ?></h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                <i class="fas fa-exchange-alt text-btn-accent text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Kredit Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_kredit) ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-purple-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Summary Row -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h4 class="font-semibold text-gray-700 mb-2">Total Dana Simpanan</h4>
        <p class="text-3xl font-bold text-btn-primary"><?= Helper::formatRupiah($total_saldo) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h4 class="font-semibold text-gray-700 mb-2">Outstanding Kredit</h4>
        <p class="text-3xl font-bold text-btn-accent"><?= Helper::formatRupiah($total_outstanding) ?></p>
    </div>
</div>

<!-- Chart & Table -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h4 class="font-semibold text-gray-700 mb-4">Transaksi 30 Hari Terakhir</h4>
        <canvas id="chartTransaksi" height="200"></canvas>
    </div>

    <!-- Nasabah Terbaru -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-gray-700">Nasabah Terbaru</h4>
            <a href="<?= BASE_URL ?>/nasabah" class="text-sm text-btn-secondary hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            <?php foreach ($nasabah_terbaru as $ns): ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-sm text-gray-800"><?= htmlspecialchars($ns['nama_lengkap']) ?></p>
                    <p class="text-xs text-gray-500"><?= $ns['no_nasabah'] ?> • <?= Helper::formatTanggal($ns['created_at']) ?></p>
                </div>
                <?= Helper::getStatusBadge($ns['status']) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
const chartData = <?= json_encode($chart_transaksi) ?>;
const labels = [...new Set(chartData.map(d => d.tanggal))];
const setoran = labels.map(l => {
    const item = chartData.find(d => d.tanggal === l && d.jenis_transaksi === 'setoran');
    return item ? parseFloat(item.total) : 0;
});
const penarikan = labels.map(l => {
    const item = chartData.find(d => d.tanggal === l && d.jenis_transaksi === 'penarikan');
    return item ? parseFloat(item.total) : 0;
});

new Chart(document.getElementById('chartTransaksi'), {
    type: 'bar',
    data: {
        labels: labels.map(l => l.slice(5)),
        datasets: [
            { label: 'Setoran', data: setoran, backgroundColor: '#003087' },
            { label: 'Penarikan', data: penarikan, backgroundColor: '#FF6600' }
        ]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt' } } },
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
