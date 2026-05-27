<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kredit - Bank BTN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; color: #333; padding: 20px; }
        .kopsurat { text-align: center; border-bottom: 3px double #003087; padding-bottom: 15px; margin-bottom: 20px; }
        .kopsurat img { height: 60px; margin-bottom: 5px; }
        .kopsurat h1 { font-size: 16pt; color: #003087; margin: 5px 0 2px; letter-spacing: 1px; }
        .kopsurat h2 { font-size: 12pt; color: #003087; font-weight: normal; }
        .kopsurat p { font-size: 9pt; color: #555; }
        .report-title { text-align: center; margin: 20px 0 10px; font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .report-info { margin-bottom: 15px; font-size: 9pt; color: #555; }
        .summary { margin-bottom: 15px; padding: 10px; background: #f0f4ff; border-radius: 5px; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9.5pt; }
        th { background: #003087; color: white; padding: 6px 8px; text-align: left; font-size: 9pt; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; font-size: 9pt; }
        .footer .ttd { text-align: center; }
        .footer .ttd p { margin-top: 50px; font-weight: bold; border-top: 1px solid #333; padding-top: 5px; display: inline-block; }
        .no-print { margin-bottom: 20px; }
        @media print { .no-print { display: none; } body { padding: 10px; } }
        @page { size: A4 landscape; margin: 15mm; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding:10px 20px;background:#003087;color:white;border:none;border-radius:5px;cursor:pointer;font-size:12pt;">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()" style="padding:10px 20px;background:#666;color:white;border:none;border-radius:5px;cursor:pointer;font-size:12pt;margin-left:10px;">✕ Tutup</button>
    </div>

    <div class="kopsurat">
        <img src="<?= BASE_URL ?>/assets/img/logo-btn.png" alt="Logo Bank BTN" onerror="this.style.display='none'">
        <h1>PT BANK TABUNGAN NEGARA (PERSERO) Tbk</h1>
        <h2>Kantor Cabang Pekanbaru</h2>
        <p>Jl. Jenderal Sudirman No. 393, Pekanbaru 28116 | Telp: (0761) 22725 | www.btn.co.id</p>
    </div>

    <div class="report-title">LAPORAN KREDIT</div>
    <div class="report-info">
        Dicetak: <?= date('d/m/Y H:i') ?> | 
        Filter: Jenis=<?= $filter['jenis_kredit'] ?: 'Semua' ?>, Kolektibilitas=<?= $filter['kolektibilitas'] ?: 'Semua' ?>, Status=<?= $filter['status'] ?: 'Semua' ?> |
        Total: <?= count($data) ?> kredit
    </div>

    <div class="summary">
        <strong>Ringkasan:</strong> Total Plafon: Rp <?= number_format(array_sum(array_column($data,'plafon')),0,',','.') ?> | Total Outstanding: Rp <?= number_format(array_sum(array_column($data,'outstanding')),0,',','.') ?>
    </div>

    <table>
        <thead>
            <tr><th>No</th><th>No. Kredit</th><th>Nasabah</th><th>Jenis</th><th class="text-right">Plafon</th><th class="text-right">Outstanding</th><th>Bunga</th><th>Tenor</th><th>Kolektibilitas</th><th>Jatuh Tempo</th></tr>
        </thead>
        <tbody>
            <?php foreach($data as $i => $d): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= $d['no_kredit'] ?></td>
                <td><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                <td><?= $d['jenis_kredit'] ?></td>
                <td class="text-right">Rp <?= number_format($d['plafon'],0,',','.') ?></td>
                <td class="text-right">Rp <?= number_format($d['outstanding'],0,',','.') ?></td>
                <td><?= $d['suku_bunga'] ?>%</td>
                <td><?= $d['tenor_bulan'] ?> bln</td>
                <td><?= ucfirst(str_replace('_',' ',$d['kolektibilitas'])) ?></td>
                <td><?= date('d/m/Y', strtotime($d['tanggal_jatuh_tempo'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="report-info">Dokumen ini dicetak secara otomatis oleh sistem.</div>
        <div class="ttd">
            <span>Pekanbaru, <?= date('d F Y') ?></span><br>
            <span>Kepala Cabang</span>
            <p>________________________</p>
        </div>
    </div>
</body>
</html>
