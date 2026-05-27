<?php require_once '../app/views/layouts/header.php'; ?>
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/transaksi" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]"><label class="block text-xs text-gray-500 mb-1">Pencarian</label><input type="text" name="search" value="<?= htmlspecialchars($filter['search']) ?>" placeholder="No. transaksi, rekening, nama..." class="w-full px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <div><label class="block text-xs text-gray-500 mb-1">Jenis</label><select name="jenis_transaksi" class="px-3 py-2 border rounded-lg text-sm outline-none"><option value="">Semua</option><option value="setoran" <?= $filter['jenis_transaksi']==='setoran'?'selected':'' ?>>Setoran</option><option value="penarikan" <?= $filter['jenis_transaksi']==='penarikan'?'selected':'' ?>>Penarikan</option><option value="transfer_keluar" <?= $filter['jenis_transaksi']==='transfer_keluar'?'selected':'' ?>>Transfer Keluar</option><option value="transfer_masuk" <?= $filter['jenis_transaksi']==='transfer_masuk'?'selected':'' ?>>Transfer Masuk</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Dari</label><input type="date" name="tanggal_dari" value="<?= $filter['tanggal_dari'] ?>" class="px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <div><label class="block text-xs text-gray-500 mb-1">Sampai</label><input type="date" name="tanggal_sampai" value="<?= $filter['tanggal_sampai'] ?>" class="px-3 py-2 border rounded-lg text-sm outline-none"></div>
        <button type="submit" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm"><i class="fas fa-search mr-1"></i>Cari</button>
        <a href="<?= BASE_URL ?>/transaksi" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Reset</a>
    </form>
</div>

<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500"><?= $pagination['total'] ?> data</p>
    <?php if(in_array(Auth::role(),['admin','cs'])): ?>
    <a href="<?= BASE_URL ?>/transaksi/create" class="px-4 py-2 bg-btn-primary text-white rounded-lg text-sm shadow"><i class="fas fa-plus mr-1"></i>Catat Transaksi</a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-btn-primary text-white"><tr><th class="px-4 py-3 text-left">No. Transaksi</th><th class="px-4 py-3 text-left">Nasabah</th><th class="px-4 py-3 text-center">Jenis</th><th class="px-4 py-3 text-right">Jumlah</th><th class="px-4 py-3 text-left">Tanggal</th><th class="px-4 py-3 text-center">Status</th><th class="px-4 py-3 text-center">Aksi</th></tr></thead>
        <tbody class="divide-y">
            <?php if(empty($transaksi)): ?><tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td></tr>
            <?php else: foreach($transaksi as $t): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs"><?= $t['no_transaksi'] ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($t['nama_lengkap']) ?><br><span class="text-xs text-gray-400"><?= $t['no_rekening'] ?></span></td>
                <td class="px-4 py-3 text-center capitalize text-xs"><?= str_replace('_',' ',$t['jenis_transaksi']) ?></td>
                <td class="px-4 py-3 text-right font-medium <?= in_array($t['jenis_transaksi'],['setoran','transfer_masuk'])?'text-green-600':'text-red-600' ?>"><?= Helper::formatRupiah($t['jumlah']) ?></td>
                <td class="px-4 py-3 text-xs"><?= Helper::formatTanggal($t['tanggal_transaksi']) ?></td>
                <td class="px-4 py-3 text-center"><?= Helper::getStatusBadge($t['status']) ?></td>
                <td class="px-4 py-3 text-center"><a href="<?= BASE_URL ?>/transaksi/detail/<?= $t['id'] ?>" class="text-btn-secondary"><i class="fas fa-eye"></i></a></td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php if ($pagination['last_page'] > 1): ?><div class="flex justify-center mt-6 gap-1"><?php for ($p=1;$p<=$pagination['last_page'];$p++): $qs=http_build_query(array_merge($filter,['page'=>$p])); ?><a href="<?= BASE_URL ?>/transaksi?<?= $qs ?>" class="px-3 py-1 rounded text-sm <?= $p===$pagination['current_page']?'bg-btn-primary text-white':'bg-white text-gray-600 border' ?>"><?= $p ?></a><?php endfor; ?></div><?php endif; ?>
<?php require_once '../app/views/layouts/footer.php'; ?>
