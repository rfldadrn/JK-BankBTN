<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Nasabah - Bank BTN</title>
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
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9.5pt; }
        th { background: #003087; color: white; padding: 6px 8px; text-align: left; font-size: 9pt; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f8f9fa; }
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

    <div class="report-title">LAPORAN DATA NASABAH</div>
    <div class="report-info">
        Dicetak: <?= date('d/m/Y H:i') ?> | 
        Filter: Status=<?= $filter['status'] ?: 'Semua' ?>, JK=<?= $filter['jenis_kelamin'] ?: 'Semua' ?><?= $filter['tanggal_dari'] ? ', Periode: '.$filter['tanggal_dari'].' s/d '.($filter['tanggal_sampai'] ?: 'sekarang') : '' ?> |
        Total: <?= count($data) ?> data
    </div>

    <table>
        <thead>
            <tr><th>No</th><th>No. Nasabah</th><th>Nama Lengkap</th><th>NIK</th><th>JK</th><th>No. Telepon</th><th>Kota</th><th>Status</th><th>Tgl Daftar</th></tr>
        </thead>
        <tbody>
            <?php foreach($data as $i => $d): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= $d['no_nasabah'] ?></td>
                <td><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                <td><?= $d['nik'] ?></td>
                <td><?= $d['jenis_kelamin'] ?></td>
                <td><?= $d['no_telepon'] ?></td>
                <td><?= $d['kota_kabupaten'] ?></td>
                <td><?= ucfirst($d['status']) ?></td>
                <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
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
