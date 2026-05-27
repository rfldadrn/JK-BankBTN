<?php $role = Auth::role(); $current = $_GET['url'] ?? ''; ?>
<aside id="sidebar" class="w-64 bg-btn-primary text-white flex flex-col fixed h-full z-40 transition-transform">
    <div class="p-5 border-b border-blue-800">
        <div class="flex items-center gap-3">
            <img src="<?= BASE_URL ?>/assets/img/logo-btn.png" alt="Logo" class="w-10 h-10 rounded" onerror="this.style.display='none'">
            <div>
                <h2 class="font-bold text-sm">Bank BTN</h2>
                <p class="text-xs text-blue-200">KC Pekanbaru</p>
            </div>
        </div>
    </div>
    <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
        <?php
        $menus = [
            ['url'=>'dashboard','icon'=>'fas fa-tachometer-alt','label'=>'Dashboard','roles'=>['admin','cs','backoffice','manager','auditor']],
            ['url'=>'nasabah','icon'=>'fas fa-users','label'=>'Nasabah','roles'=>['admin','cs','backoffice','manager']],
            ['url'=>'rekening','icon'=>'fas fa-university','label'=>'Rekening','roles'=>['admin','cs','backoffice']],
            ['url'=>'transaksi','icon'=>'fas fa-exchange-alt','label'=>'Transaksi','roles'=>['admin','cs','manager']],
            ['url'=>'kredit','icon'=>'fas fa-file-invoice-dollar','label'=>'Kredit','roles'=>['admin','backoffice','manager']],
            ['url'=>'laporan','icon'=>'fas fa-chart-bar','label'=>'Laporan','roles'=>['admin','manager','auditor']],
            ['url'=>'users','icon'=>'fas fa-user-cog','label'=>'Manajemen User','roles'=>['admin']],
            ['url'=>'profile','icon'=>'fas fa-id-card','label'=>'Profil Saya','roles'=>['admin','cs','backoffice','manager','auditor']],
        ];
        foreach ($menus as $menu):
            if (!in_array($role, $menu['roles'])) continue;
            $active = str_starts_with($current, $menu['url']) ? 'active' : '';
        ?>
        <a href="<?= BASE_URL ?>/<?= $menu['url'] ?>"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm <?= $active ?> text-blue-100 hover:text-white transition-colors">
            <i class="<?= $menu['icon'] ?> w-5 text-center"></i>
            <span><?= $menu['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>
    <div class="p-4 border-t border-blue-800 text-xs text-blue-200">
        <p><?= htmlspecialchars(Auth::user()['nama'] ?? '') ?></p>
        <p class="mt-1 capitalize font-medium text-blue-100"><?= Auth::role() ?></p>
    </div>
</aside>
