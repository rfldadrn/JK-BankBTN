const {
  Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell,
  HeadingLevel, AlignmentType, BorderStyle, WidthType, ShadingType,
  LevelFormat, PageNumber, PageBreak, Header, Footer, TabStopType,
  TabStopPosition, VerticalAlign
} = require('docx');
const fs = require('fs');

const border = { style: BorderStyle.SINGLE, size: 1, color: "CCCCCC" };
const borders = { top: border, bottom: border, left: border, right: border };
const headerBorder = { style: BorderStyle.SINGLE, size: 1, color: "1A5276" };
const headerBorders = { top: headerBorder, bottom: headerBorder, left: headerBorder, right: headerBorder };

const cellMargins = { top: 100, bottom: 100, left: 150, right: 150 };

function heading1(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_1,
    spacing: { before: 360, after: 120 },
    children: [new TextRun({ text, bold: true, size: 28, color: "1A5276" })]
  });
}

function heading2(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_2,
    spacing: { before: 240, after: 80 },
    children: [new TextRun({ text, bold: true, size: 24, color: "2E86C1" })]
  });
}

function heading3(text) {
  return new Paragraph({
    spacing: { before: 180, after: 60 },
    children: [new TextRun({ text, bold: true, size: 22, color: "1B4F72" })]
  });
}

function body(text, opts = {}) {
  return new Paragraph({
    spacing: { before: 60, after: 60 },
    children: [new TextRun({ text, size: 22, ...opts })]
  });
}

function bullet(text, level = 0) {
  return new Paragraph({
    numbering: { reference: "bullets", level },
    spacing: { before: 40, after: 40 },
    children: [new TextRun({ text, size: 22 })]
  });
}

function numbered(text, level = 0) {
  return new Paragraph({
    numbering: { reference: "numbers", level },
    spacing: { before: 40, after: 40 },
    children: [new TextRun({ text, size: 22 })]
  });
}

function spacer() {
  return new Paragraph({ children: [new TextRun("")], spacing: { before: 60, after: 60 } });
}

function pageBreak() {
  return new Paragraph({ children: [new PageBreak()] });
}

function headerRow(cells, widths) {
  return new TableRow({
    tableHeader: true,
    children: cells.map((text, i) => new TableCell({
      borders: headerBorders,
      width: { size: widths[i], type: WidthType.DXA },
      margins: cellMargins,
      shading: { fill: "1A5276", type: ShadingType.CLEAR },
      verticalAlign: VerticalAlign.CENTER,
      children: [new Paragraph({ alignment: AlignmentType.CENTER, children: [new TextRun({ text, bold: true, size: 20, color: "FFFFFF" })] })]
    }))
  });
}

function dataRow(cells, widths, shaded = false) {
  return new TableRow({
    children: cells.map((text, i) => new TableCell({
      borders,
      width: { size: widths[i], type: WidthType.DXA },
      margins: cellMargins,
      shading: { fill: shaded ? "EBF5FB" : "FFFFFF", type: ShadingType.CLEAR },
      children: [new Paragraph({ children: [new TextRun({ text, size: 20 })] })]
    }))
  });
}

function makeTable(headers, rows, widths) {
  const totalWidth = widths.reduce((a, b) => a + b, 0);
  return new Table({
    width: { size: totalWidth, type: WidthType.DXA },
    columnWidths: widths,
    rows: [
      headerRow(headers, widths),
      ...rows.map((r, i) => dataRow(r, widths, i % 2 === 1))
    ]
  });
}

// ===== DOCUMENT CONTENT =====

const doc = new Document({
  numbering: {
    config: [
      {
        reference: "bullets",
        levels: [
          { level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 720, hanging: 360 } } } },
          { level: 1, format: LevelFormat.BULLET, text: "◦", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 1080, hanging: 360 } } } },
        ]
      },
      {
        reference: "numbers",
        levels: [
          { level: 0, format: LevelFormat.DECIMAL, text: "%1.", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 720, hanging: 360 } } } },
          { level: 1, format: LevelFormat.LOWER_LETTER, text: "%2.", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 1080, hanging: 360 } } } },
        ]
      }
    ]
  },
  styles: {
    default: { document: { run: { font: "Arial", size: 22 } } },
    paragraphStyles: [
      {
        id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 28, bold: true, font: "Arial", color: "1A5276" },
        paragraph: { spacing: { before: 360, after: 120 }, outlineLevel: 0 }
      },
      {
        id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 24, bold: true, font: "Arial", color: "2E86C1" },
        paragraph: { spacing: { before: 240, after: 80 }, outlineLevel: 1 }
      },
    ]
  },
  sections: [{
    properties: {
      page: {
        size: { width: 11906, height: 16838 },
        margin: { top: 1440, right: 1260, bottom: 1440, left: 1440 }
      }
    },
    headers: {
      default: new Header({
        children: [
          new Paragraph({
            border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: "1A5276", space: 1 } },
            spacing: { before: 0, after: 120 },
            children: [
              new TextRun({ text: "PRD — Sistem Informasi Pengelolaan Data Nasabah Bank BTN", bold: true, size: 18, color: "1A5276" }),
              new TextRun({ text: "     |     Kantor Cabang Pekanbaru", size: 18, color: "888888" })
            ]
          })
        ]
      })
    },
    footers: {
      default: new Footer({
        children: [
          new Paragraph({
            border: { top: { style: BorderStyle.SINGLE, size: 4, color: "1A5276", space: 1 } },
            spacing: { before: 120 },
            tabStops: [{ type: TabStopType.RIGHT, position: TabStopPosition.MAX }],
            children: [
              new TextRun({ text: "Konfidensial — Dokumen Internal", size: 16, color: "888888" }),
              new TextRun({ text: "\tHalaman ", size: 16, color: "888888" }),
              new TextRun({ children: [PageNumber.CURRENT], size: 16, color: "1A5276" })
            ]
          })
        ]
      })
    },
    children: [

      // ===== COVER =====
      new Paragraph({ spacing: { before: 1200, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "PRODUCT REQUIREMENTS DOCUMENT", bold: true, size: 40, color: "1A5276" })] }),
      new Paragraph({ spacing: { before: 160, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "PRD v1.0", size: 24, color: "2E86C1", bold: true })] }),
      spacer(),
      new Paragraph({ spacing: { before: 200, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "SISTEM INFORMASI PENGELOLAAN DATA NASABAH", bold: true, size: 34, color: "1B4F72" })] }),
      new Paragraph({ spacing: { before: 80, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "BANK BTN BERBASIS WEB", bold: true, size: 30, color: "1B4F72" })] }),
      new Paragraph({ spacing: { before: 80, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "Kantor Cabang Pekanbaru", size: 26, color: "555555" })] }),
      spacer(), spacer(),
      new Paragraph({ spacing: { before: 80, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "Tugas Akhir — Perancangan Sistem Informasi", size: 22, color: "777777" })] }),
      new Paragraph({ spacing: { before: 60, after: 0 }, alignment: AlignmentType.CENTER, children: [new TextRun({ text: "2024 / 2025", size: 22, color: "777777" })] }),

      pageBreak(),

      // ===== 1. OVERVIEW =====
      heading1("1. RINGKASAN PROYEK (Project Overview)"),
      heading2("1.1 Latar Belakang"),
      body("Bank BTN Kantor Cabang Pekanbaru saat ini masih mengelola data nasabah secara manual menggunakan Microsoft Excel dan dokumen fisik. Kondisi ini menimbulkan berbagai masalah operasional yang menghambat efisiensi pelayanan kepada nasabah."),
      spacer(),
      heading2("1.2 Permasalahan yang Diidentifikasi"),
      makeTable(
        ["No", "Permasalahan", "Dampak", "Prioritas"],
        [
          ["1", "Pengolahan data manual via Excel & dokumen fisik", "Input lambat, tidak terstruktur, rawan human error", "HIGH"],
          ["2", "Pencarian data membuka file satu-per-satu", "Pelayanan nasabah terlambat", "HIGH"],
          ["3", "Duplikasi & inkonsistensi data (tidak ada DB terpusat)", "Data nasabah tersimpan ganda dengan info berbeda", "HIGH"],
          ["4", "Pelaporan manual dengan menggabungkan file", "Tidak real-time, memperlambat pengambilan keputusan", "MEDIUM"],
        ],
        [600, 3200, 3200, 1200]
      ),
      spacer(),
      heading2("1.3 Tujuan Sistem"),
      numbered("Merancang sistem informasi pengelolaan data nasabah berbasis web yang terintegrasi."),
      numbered("Menyediakan sistem pencarian data nasabah yang cepat dan akurat."),
      numbered("Menghilangkan duplikasi data dengan database terpusat."),
      numbered("Menghasilkan laporan secara otomatis dan real-time."),
      numbered("Meningkatkan keamanan data nasabah."),
      spacer(),
      heading2("1.4 Ruang Lingkup (Scope)"),
      body("Sistem ini mencakup:"),
      bullet("Pengelolaan data identitas nasabah"),
      bullet("Pengelolaan data rekening"),
      bullet("Pengelolaan data transaksi (setoran, penarikan, transfer)"),
      bullet("Pengelolaan data kredit/pembiayaan"),
      bullet("Manajemen status nasabah (aktif/nonaktif)"),
      bullet("Pelaporan dan ekspor data"),
      bullet("Manajemen pengguna dan hak akses (role-based)"),
      spacer(),
      body("Sistem ini TIDAK mencakup:", { bold: true }),
      bullet("Integrasi dengan core banking system Bank BTN pusat (fase 1)"),
      bullet("Pemrosesan transaksi keuangan real-time (hanya pencatatan)"),
      bullet("Mobile application (hanya web-based)"),

      pageBreak(),

      // ===== 2. STAKEHOLDERS & ROLES =====
      heading1("2. STAKEHOLDER & ROLE PENGGUNA"),
      heading2("2.1 Deskripsi Role"),
      makeTable(
        ["Role", "Deskripsi", "Jumlah User (Est.)", "Level Akses"],
        [
          ["Admin Sistem", "Superadmin, mengelola seluruh sistem & user", "1-2 orang", "Full Access"],
          ["Petugas Front-end (CS)", "Input & update data nasabah, layanan langsung", "3-5 orang", "CRUD Nasabah"],
          ["Petugas Back-office", "Verifikasi data, kelola rekening & kredit", "2-3 orang", "Read + Verify"],
          ["Kepala Cabang / Manager", "Monitoring dashboard, akses laporan", "1-2 orang", "Read + Report"],
          ["Auditor Internal", "Audit trail, log aktivitas, laporan kepatuhan", "1-2 orang", "Read Only"],
        ],
        [1600, 2800, 1600, 1400]
      ),
      spacer(),
      heading2("2.2 Matriks Hak Akses (CRUD per Modul)"),
      makeTable(
        ["Modul", "Admin", "CS/Front-end", "Back-office", "Manager", "Auditor"],
        [
          ["Data Nasabah", "C R U D", "C R U", "R U (verif)", "R", "R"],
          ["Data Rekening", "C R U D", "C R U", "C R U D", "R", "R"],
          ["Data Transaksi", "C R U D", "C R U", "R U", "R", "R"],
          ["Data Kredit", "C R U D", "R", "C R U D", "R", "R"],
          ["Laporan", "C R U D", "R (terbatas)", "R", "R (full)", "R (full)"],
          ["Manajemen User", "C R U D", "-", "-", "R", "-"],
          ["Log Aktivitas", "R", "-", "-", "R", "R (full)"],
        ],
        [1800, 1100, 1300, 1300, 1200, 1100]
      ),
      body("Keterangan: C=Create, R=Read, U=Update, D=Delete"),
      spacer(),

      pageBreak(),

      // ===== 3. FITUR & REQUIREMENTS =====
      heading1("3. FITUR & FUNCTIONAL REQUIREMENTS"),

      heading2("3.1 Modul Autentikasi & Manajemen User"),
      makeTable(
        ["ID", "Fitur", "Deskripsi", "Prioritas"],
        [
          ["AUTH-01", "Login", "Login dengan username & password, session management", "P0"],
          ["AUTH-02", "Logout", "Logout manual & auto-logout setelah timeout (30 menit)", "P0"],
          ["AUTH-03", "Manajemen User", "Admin membuat, edit, nonaktifkan akun user", "P0"],
          ["AUTH-04", "Role Assignment", "Admin assign role ke user", "P0"],
          ["AUTH-05", "Reset Password", "Admin reset password user", "P1"],
          ["AUTH-06", "Audit Trail", "Log semua aktivitas login/logout & perubahan data", "P1"],
        ],
        [900, 1800, 3400, 800]
      ),
      spacer(),

      heading2("3.2 Modul Manajemen Data Nasabah"),
      makeTable(
        ["ID", "Fitur", "Deskripsi", "Prioritas"],
        [
          ["NAS-01", "Input Data Nasabah Baru", "Form input lengkap identitas nasabah + validasi", "P0"],
          ["NAS-02", "Edit Data Nasabah", "Update informasi nasabah dengan riwayat perubahan", "P0"],
          ["NAS-03", "Pencarian Nasabah", "Cari berdasar nama, NIK, nomor rekening, telepon", "P0"],
          ["NAS-04", "Detail Nasabah", "Halaman profil lengkap + semua rekening & riwayat", "P0"],
          ["NAS-05", "Nonaktifkan Nasabah", "Soft-delete, ubah status aktif/nonaktif", "P0"],
          ["NAS-06", "Import Data", "Import data massal dari file CSV/Excel", "P2"],
          ["NAS-07", "Duplikasi Check", "Deteksi otomatis duplikasi data saat input (via NIK)", "P1"],
        ],
        [900, 2000, 3200, 800]
      ),
      spacer(),

      heading2("3.3 Modul Manajemen Rekening"),
      makeTable(
        ["ID", "Fitur", "Deskripsi", "Prioritas"],
        [
          ["REK-01", "Buat Rekening Baru", "Buka rekening baru untuk nasabah terdaftar", "P0"],
          ["REK-02", "Lihat Detail Rekening", "Informasi rekening + saldo + riwayat transaksi", "P0"],
          ["REK-03", "Update Status Rekening", "Aktif / Beku / Tutup rekening", "P0"],
          ["REK-04", "Daftar Rekening Nasabah", "Lihat semua rekening milik 1 nasabah", "P0"],
          ["REK-05", "Jenis Rekening", "Tabungan, Giro, Deposito, BTN Batara, dll.", "P1"],
        ],
        [900, 2000, 3200, 800]
      ),
      spacer(),

      heading2("3.4 Modul Pencatatan Transaksi"),
      makeTable(
        ["ID", "Fitur", "Deskripsi", "Prioritas"],
        [
          ["TRX-01", "Catat Setoran", "Input setoran tunai ke rekening", "P0"],
          ["TRX-02", "Catat Penarikan", "Input penarikan dari rekening", "P0"],
          ["TRX-03", "Catat Transfer", "Input transfer antar rekening internal cabang", "P0"],
          ["TRX-04", "Riwayat Transaksi", "Filter transaksi per tanggal, jenis, rekening", "P0"],
          ["TRX-05", "Bukti Transaksi", "Generate & cetak/download slip transaksi (PDF)", "P1"],
        ],
        [900, 1800, 3400, 800]
      ),
      spacer(),

      heading2("3.5 Modul Kredit / Pembiayaan"),
      makeTable(
        ["ID", "Fitur", "Deskripsi", "Prioritas"],
        [
          ["KRD-01", "Input Data Kredit", "Plafon, jangka waktu, suku bunga, tujuan kredit", "P0"],
          ["KRD-02", "Jadwal Angsuran", "Generate & tampilkan tabel amortisasi", "P1"],
          ["KRD-03", "Update Status Kredit", "Lancar / Kurang Lancar / Macet", "P0"],
          ["KRD-04", "Riwayat Pembayaran", "Rekam pembayaran angsuran bulanan", "P1"],
          ["KRD-05", "Monitoring Kredit", "Dashboard kredit per nasabah", "P1"],
        ],
        [900, 1900, 3300, 800]
      ),
      spacer(),

      heading2("3.6 Modul Laporan"),
      makeTable(
        ["ID", "Fitur", "Deskripsi", "Prioritas"],
        [
          ["LAP-01", "Laporan Data Nasabah", "Daftar nasabah + filter status, periode, jenis", "P0"],
          ["LAP-02", "Laporan Transaksi", "Rekap transaksi per periode, per rekening", "P0"],
          ["LAP-03", "Laporan Kredit", "Status kredit, tunggakan, rekap pembiayaan", "P0"],
          ["LAP-04", "Laporan Rekening", "Rekap pembukaan/penutupan rekening", "P1"],
          ["LAP-05", "Export PDF & Excel", "Semua laporan dapat di-export ke PDF dan Excel", "P1"],
          ["LAP-06", "Dashboard Ringkasan", "Chart & statistik: jumlah nasabah, transaksi, kredit", "P1"],
        ],
        [900, 1800, 3400, 800]
      ),
      body("Prioritas: P0=Must Have, P1=Should Have, P2=Nice to Have"),
      spacer(),

      pageBreak(),

      // ===== 4. USER FLOW =====
      heading1("4. USER FLOW & NAVIGASI APLIKASI"),

      heading2("4.1 Flow Utama Aplikasi"),
      body("Berikut adalah alur navigasi utama sistem:"),
      spacer(),
      makeTable(
        ["Flow", "Langkah", "Actor", "Output"],
        [
          ["Login", "Buka app → Input username/password → Validasi → Dashboard", "Semua User", "Session aktif + Dashboard sesuai role"],
          ["Daftar Nasabah Baru", "Dashboard → Menu Nasabah → Tambah Nasabah → Isi Form → Validasi → Simpan", "CS / Admin", "Data nasabah tersimpan, ID otomatis"],
          ["Cari & Lihat Nasabah", "Menu Nasabah → Kolom Pencarian → Pilih nasabah → Detail Page", "Semua (sesuai role)", "Halaman detail nasabah"],
          ["Buka Rekening", "Detail Nasabah → Tab Rekening → Tambah Rekening → Isi form → Simpan", "CS / Back-office", "Rekening baru aktif"],
          ["Input Transaksi", "Menu Transaksi → Pilih Jenis → Input data → Konfirmasi → Simpan", "CS", "Transaksi tercatat + Slip"],
          ["Buat Laporan", "Menu Laporan → Pilih jenis laporan → Set filter → Generate → Export", "Manager / Admin", "File PDF atau Excel"],
          ["Kelola User", "Settings → Manajemen User → Tambah/Edit → Set Role → Simpan", "Admin", "User baru aktif"],
          ["Logout", "Klik avatar → Logout → Konfirmasi → Halaman Login", "Semua User", "Session dihapus"],
        ],
        [1400, 3000, 1500, 1800]
      ),
      spacer(),

      heading2("4.2 Struktur Menu Navigasi"),
      makeTable(
        ["Menu Utama", "Submenu", "Role yang Dapat Akses"],
        [
          ["🏠 Dashboard", "Ringkasan statistik, notifikasi", "Semua"],
          ["👥 Nasabah", "Daftar Nasabah, Tambah Nasabah, Cari Nasabah", "Admin, CS, Back-office, Manager"],
          ["🏦 Rekening", "Daftar Rekening, Buka Rekening, Status Rekening", "Admin, CS, Back-office"],
          ["💳 Transaksi", "Catat Transaksi, Riwayat Transaksi, Cetak Slip", "Admin, CS"],
          ["📋 Kredit", "Data Kredit, Jadwal Angsuran, Status Kredit", "Admin, Back-office"],
          ["📊 Laporan", "Lap. Nasabah, Lap. Transaksi, Lap. Kredit, Export", "Admin, Manager, Auditor"],
          ["⚙️ Pengaturan", "Manajemen User, Role, Log Aktivitas, Profil", "Admin (penuh), lain (terbatas)"],
        ],
        [1600, 3000, 2200]
      ),
      spacer(),

      pageBreak(),

      // ===== 5. DESIGN SISTEM =====
      heading1("5. SPESIFIKASI DESAIN SISTEM"),

      heading2("5.1 Wireframe Halaman Utama"),
      makeTable(
        ["Halaman", "Komponen Utama", "Catatan Desain"],
        [
          ["Login Page", "Logo BTN, form username/password, tombol masuk, info versi", "Minimalis, warna biru BTN (#003087)"],
          ["Dashboard", "Sidebar nav, header, 4 card stats (nasabah/rekening/transaksi/kredit), grafik bar transaksi bulanan, tabel nasabah terbaru", "Responsive, chart library: Chart.js"],
          ["Daftar Nasabah", "Search bar, filter dropdown, tabel data (paginated 25/page), action button (lihat/edit), tombol tambah", "Sortable columns, export button"],
          ["Form Nasabah", "Tab: Data Pribadi | Alamat | Dokumen, field validasi, upload KTP, tombol simpan/batal", "Validation real-time, progress indicator"],
          ["Detail Nasabah", "Card info pribadi, tab: Rekening | Transaksi | Kredit | Dokumen, timeline aktivitas", "Print-friendly view"],
          ["Laporan", "Date-range picker, filter multi-select, preview table, tombol Export PDF/Excel", "Auto-generate on filter change"],
        ],
        [1600, 3200, 2000]
      ),
      spacer(),

      heading2("5.2 Design System & Tech Stack"),
      makeTable(
        ["Komponen", "Pilihan Teknologi", "Keterangan"],
        [
          ["Frontend Framework", "Vue.js 3 / React.js", "SPA (Single Page Application)"],
          ["CSS Framework", "Tailwind CSS", "Utility-first, responsif"],
          ["Backend Framework", "Laravel 10 (PHP) / Node.js Express", "RESTful API"],
          ["Database", "MySQL 8.0 / PostgreSQL", "Relasional, ACID compliant"],
          ["Authentication", "Laravel Sanctum / JWT", "Token-based, session management"],
          ["PDF Generator", "DomPDF (Laravel) / Puppeteer", "Laporan & slip transaksi"],
          ["Chart Library", "Chart.js / Recharts", "Dashboard & laporan visual"],
          ["File Storage", "Local Storage / AWS S3", "Dokumen nasabah"],
          ["Web Server", "Apache / Nginx", "Reverse proxy"],
          ["Version Control", "Git (GitHub/GitLab)", "Source code management"],
        ],
        [2000, 2200, 2600]
      ),
      spacer(),

      heading2("5.3 Non-Functional Requirements"),
      makeTable(
        ["Kategori", "Requirement", "Target"],
        [
          ["Performance", "Waktu load halaman", "< 3 detik untuk data < 1000 baris"],
          ["Performance", "Response API", "< 500ms untuk operasi CRUD biasa"],
          ["Security", "Enkripsi password", "bcrypt dengan salt rounds min. 12"],
          ["Security", "HTTPS", "SSL/TLS wajib di production"],
          ["Security", "Session timeout", "Auto logout 30 menit tidak aktif"],
          ["Security", "Input sanitasi", "Proteksi SQL Injection & XSS wajib"],
          ["Availability", "Uptime", "99% pada jam kerja (08.00-17.00 WIB)"],
          ["Usability", "Browser support", "Chrome, Firefox, Edge (2 versi terakhir)"],
          ["Usability", "Responsive", "Desktop-first, support tablet (min 768px)"],
          ["Data", "Backup otomatis", "Backup harian, retensi 30 hari"],
        ],
        [1500, 2500, 2800]
      ),

      pageBreak(),

      // ===== 6. DATABASE DESIGN =====
      heading1("6. DESAIN DATABASE & RELASI TABEL"),

      heading2("6.1 Diagram Relasi (ERD — Ringkasan)"),
      body("Berikut adalah deskripsi relasi antar entitas utama dalam sistem:"),
      spacer(),
      makeTable(
        ["Relasi", "Dari Tabel", "Ke Tabel", "Tipe", "Keterangan"],
        [
          ["Nasabah → Rekening", "nasabah (id)", "rekening (nasabah_id)", "One-to-Many", "1 nasabah bisa punya banyak rekening"],
          ["Rekening → Transaksi", "rekening (id)", "transaksi (rekening_id)", "One-to-Many", "1 rekening bisa punya banyak transaksi"],
          ["Nasabah → Kredit", "nasabah (id)", "kredit (nasabah_id)", "One-to-Many", "1 nasabah bisa punya banyak kredit"],
          ["Kredit → Angsuran", "kredit (id)", "angsuran (kredit_id)", "One-to-Many", "1 kredit punya banyak cicilan"],
          ["User → Role", "users (id)", "user_roles (user_id)", "Many-to-Many", "1 user bisa punya banyak role"],
          ["User → Log", "users (id)", "activity_logs (user_id)", "One-to-Many", "Semua aktivitas user dicatat"],
        ],
        [1700, 1600, 1600, 1300, 2000]
      ),
      spacer(),

      heading2("6.2 Desain Tabel Detail"),

      heading3("Tabel: nasabah"),
      makeTable(
        ["Kolom", "Tipe Data", "Null", "Default", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED", "NO", "AUTO_INCREMENT", "Primary Key"],
          ["no_nasabah", "VARCHAR(20)", "NO", "-", "Nomor nasabah unik, generated"],
          ["nik", "VARCHAR(16)", "NO", "-", "Nomor Induk Kependudukan, UNIQUE"],
          ["nama_lengkap", "VARCHAR(255)", "NO", "-", "Nama lengkap sesuai KTP"],
          ["tempat_lahir", "VARCHAR(100)", "YES", "NULL", "Kota tempat lahir"],
          ["tanggal_lahir", "DATE", "NO", "-", "Tanggal lahir"],
          ["jenis_kelamin", "ENUM('L','P')", "NO", "-", "L=Laki-laki, P=Perempuan"],
          ["agama", "VARCHAR(20)", "YES", "NULL", "Agama nasabah"],
          ["status_perkawinan", "ENUM('lajang','menikah','cerai')", "YES", "lajang", ""],
          ["pekerjaan", "VARCHAR(100)", "YES", "NULL", "Jenis pekerjaan"],
          ["penghasilan_bulanan", "DECIMAL(15,2)", "YES", "NULL", "Estimasi pendapatan"],
          ["no_telepon", "VARCHAR(20)", "NO", "-", "Nomor HP aktif"],
          ["email", "VARCHAR(255)", "YES", "NULL", "Alamat email"],
          ["alamat", "TEXT", "NO", "-", "Alamat lengkap"],
          ["rt_rw", "VARCHAR(10)", "YES", "NULL", "RT/RW"],
          ["kelurahan", "VARCHAR(100)", "YES", "NULL", ""],
          ["kecamatan", "VARCHAR(100)", "YES", "NULL", ""],
          ["kota_kabupaten", "VARCHAR(100)", "NO", "-", ""],
          ["provinsi", "VARCHAR(100)", "NO", "-", ""],
          ["kode_pos", "VARCHAR(10)", "YES", "NULL", ""],
          ["no_ktp_path", "VARCHAR(500)", "YES", "NULL", "Path file scan KTP"],
          ["status", "ENUM('aktif','nonaktif','blacklist')", "NO", "aktif", "Status nasabah"],
          ["created_by", "BIGINT UNSIGNED", "YES", "NULL", "FK → users.id"],
          ["updated_by", "BIGINT UNSIGNED", "YES", "NULL", "FK → users.id"],
          ["created_at", "TIMESTAMP", "NO", "CURRENT_TIMESTAMP", ""],
          ["updated_at", "TIMESTAMP", "YES", "NULL", "Auto-update"],
          ["deleted_at", "TIMESTAMP", "YES", "NULL", "Soft delete"],
        ],
        [1800, 1800, 600, 1400, 2000]
      ),
      spacer(),

      heading3("Tabel: rekening"),
      makeTable(
        ["Kolom", "Tipe Data", "Null", "Default", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED", "NO", "AUTO_INCREMENT", "Primary Key"],
          ["no_rekening", "VARCHAR(20)", "NO", "-", "Nomor rekening unik, UNIQUE"],
          ["nasabah_id", "BIGINT UNSIGNED", "NO", "-", "FK → nasabah.id"],
          ["jenis_rekening", "ENUM('tabungan','giro','deposito','batara')", "NO", "tabungan", "Jenis produk perbankan"],
          ["nama_produk", "VARCHAR(100)", "YES", "NULL", "Nama produk rekening spesifik"],
          ["saldo", "DECIMAL(15,2)", "NO", "0.00", "Saldo terkini"],
          ["saldo_minimum", "DECIMAL(15,2)", "YES", "50000.00", "Saldo minimum produk"],
          ["mata_uang", "VARCHAR(5)", "NO", "IDR", "Kode mata uang ISO"],
          ["tanggal_buka", "DATE", "NO", "-", "Tanggal pembukaan rekening"],
          ["tanggal_tutup", "DATE", "YES", "NULL", "Tanggal penutupan jika ditutup"],
          ["status", "ENUM('aktif','beku','tutup')", "NO", "aktif", "Status rekening"],
          ["keterangan", "TEXT", "YES", "NULL", "Catatan tambahan"],
          ["created_by", "BIGINT UNSIGNED", "YES", "NULL", "FK → users.id"],
          ["created_at", "TIMESTAMP", "NO", "CURRENT_TIMESTAMP", ""],
          ["updated_at", "TIMESTAMP", "YES", "NULL", ""],
        ],
        [1800, 1800, 600, 1400, 2000]
      ),
      spacer(),

      heading3("Tabel: transaksi"),
      makeTable(
        ["Kolom", "Tipe Data", "Null", "Default", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED", "NO", "AUTO_INCREMENT", "Primary Key"],
          ["no_transaksi", "VARCHAR(30)", "NO", "-", "Nomor transaksi unik, generated"],
          ["rekening_id", "BIGINT UNSIGNED", "NO", "-", "FK → rekening.id (rekening debet)"],
          ["rekening_tujuan_id", "BIGINT UNSIGNED", "YES", "NULL", "FK → rekening.id (transfer)"],
          ["jenis_transaksi", "ENUM('setoran','penarikan','transfer_masuk','transfer_keluar')", "NO", "-", ""],
          ["jumlah", "DECIMAL(15,2)", "NO", "-", "Nominal transaksi"],
          ["saldo_sebelum", "DECIMAL(15,2)", "NO", "-", "Snapshot saldo sebelum transaksi"],
          ["saldo_sesudah", "DECIMAL(15,2)", "NO", "-", "Snapshot saldo setelah transaksi"],
          ["keterangan", "VARCHAR(255)", "YES", "NULL", "Deskripsi / berita transaksi"],
          ["tanggal_transaksi", "DATETIME", "NO", "NOW()", "Waktu transaksi"],
          ["teller_id", "BIGINT UNSIGNED", "NO", "-", "FK → users.id (petugas input)"],
          ["status", "ENUM('sukses','batal','pending')", "NO", "sukses", ""],
          ["created_at", "TIMESTAMP", "NO", "CURRENT_TIMESTAMP", ""],
        ],
        [1800, 1800, 600, 1400, 2000]
      ),
      spacer(),

      heading3("Tabel: kredit"),
      makeTable(
        ["Kolom", "Tipe Data", "Null", "Default", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED", "NO", "AUTO_INCREMENT", "Primary Key"],
          ["no_kredit", "VARCHAR(25)", "NO", "-", "Nomor kredit unik"],
          ["nasabah_id", "BIGINT UNSIGNED", "NO", "-", "FK → nasabah.id"],
          ["jenis_kredit", "VARCHAR(100)", "NO", "-", "KPR, KKB, KMK, KTA, dll."],
          ["plafon", "DECIMAL(15,2)", "NO", "-", "Jumlah kredit yang disetujui"],
          ["outstanding", "DECIMAL(15,2)", "NO", "-", "Sisa pokok yang belum dibayar"],
          ["suku_bunga", "DECIMAL(5,2)", "NO", "-", "Suku bunga per tahun (%)"],
          ["tenor_bulan", "INT", "NO", "-", "Jangka waktu dalam bulan"],
          ["angsuran_per_bulan", "DECIMAL(15,2)", "NO", "-", "Besaran cicilan bulanan"],
          ["tanggal_mulai", "DATE", "NO", "-", "Tanggal pencairan"],
          ["tanggal_jatuh_tempo", "DATE", "NO", "-", "Tanggal lunas kontrak"],
          ["tujuan_kredit", "TEXT", "YES", "NULL", "Keterangan penggunaan kredit"],
          ["jaminan", "VARCHAR(255)", "YES", "NULL", "Deskripsi agunan/jaminan"],
          ["kolektibilitas", "ENUM('lancar','perhatian_khusus','kurang_lancar','diragukan','macet')", "NO", "lancar", "Kualitas kredit BI"],
          ["status", "ENUM('aktif','lunas','hapus_buku','macet')", "NO", "aktif", ""],
          ["created_by", "BIGINT UNSIGNED", "YES", "NULL", "FK → users.id"],
          ["created_at", "TIMESTAMP", "NO", "CURRENT_TIMESTAMP", ""],
          ["updated_at", "TIMESTAMP", "YES", "NULL", ""],
        ],
        [1800, 1800, 600, 1400, 2000]
      ),
      spacer(),

      heading3("Tabel: angsuran"),
      makeTable(
        ["Kolom", "Tipe Data", "Null", "Default", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED", "NO", "AUTO_INCREMENT", "Primary Key"],
          ["kredit_id", "BIGINT UNSIGNED", "NO", "-", "FK → kredit.id"],
          ["periode_ke", "INT", "NO", "-", "Urutan cicilan ke-N"],
          ["tanggal_jatuh_tempo", "DATE", "NO", "-", "Tanggal cicilan harus dibayar"],
          ["tanggal_bayar", "DATE", "YES", "NULL", "Tanggal realisasi pembayaran"],
          ["pokok", "DECIMAL(15,2)", "NO", "-", "Komponen pokok"],
          ["bunga", "DECIMAL(15,2)", "NO", "-", "Komponen bunga"],
          ["total_angsuran", "DECIMAL(15,2)", "NO", "-", "pokok + bunga"],
          ["denda", "DECIMAL(15,2)", "YES", "0.00", "Denda keterlambatan"],
          ["status", "ENUM('belum_bayar','lunas','terlambat')", "NO", "belum_bayar", ""],
          ["created_at", "TIMESTAMP", "NO", "CURRENT_TIMESTAMP", ""],
          ["updated_at", "TIMESTAMP", "YES", "NULL", ""],
        ],
        [1800, 1800, 600, 1400, 2000]
      ),
      spacer(),

      heading3("Tabel: users"),
      makeTable(
        ["Kolom", "Tipe Data", "Null", "Default", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED", "NO", "AUTO_INCREMENT", "Primary Key"],
          ["nama", "VARCHAR(255)", "NO", "-", "Nama lengkap pegawai"],
          ["username", "VARCHAR(100)", "NO", "-", "Username login, UNIQUE"],
          ["email", "VARCHAR(255)", "NO", "-", "Email pegawai, UNIQUE"],
          ["password", "VARCHAR(255)", "NO", "-", "Hash bcrypt"],
          ["nip", "VARCHAR(20)", "YES", "NULL", "Nomor Induk Pegawai"],
          ["jabatan", "VARCHAR(100)", "YES", "NULL", "Jabatan di kantor"],
          ["no_telepon", "VARCHAR(20)", "YES", "NULL", ""],
          ["status", "ENUM('aktif','nonaktif')", "NO", "aktif", "Status akun"],
          ["last_login_at", "TIMESTAMP", "YES", "NULL", "Waktu login terakhir"],
          ["created_at", "TIMESTAMP", "NO", "CURRENT_TIMESTAMP", ""],
          ["updated_at", "TIMESTAMP", "YES", "NULL", ""],
        ],
        [1800, 1800, 600, 1400, 2000]
      ),
      spacer(),

      heading3("Tabel: roles & user_roles"),
      makeTable(
        ["Tabel", "Kolom", "Tipe", "Keterangan"],
        [
          ["roles", "id", "BIGINT PK", "Primary Key"],
          ["roles", "name", "VARCHAR(50) UNIQUE", "Nama role: admin, cs, backoffice, manager, auditor"],
          ["roles", "description", "TEXT", "Deskripsi role"],
          ["user_roles", "id", "BIGINT PK", "Primary Key"],
          ["user_roles", "user_id", "BIGINT FK", "FK → users.id"],
          ["user_roles", "role_id", "BIGINT FK", "FK → roles.id"],
          ["user_roles", "created_at", "TIMESTAMP", "Waktu assignment role"],
        ],
        [1500, 1500, 1800, 2800]
      ),
      spacer(),

      heading3("Tabel: activity_logs"),
      makeTable(
        ["Kolom", "Tipe Data", "Keterangan"],
        [
          ["id", "BIGINT UNSIGNED AUTO_INCREMENT", "Primary Key"],
          ["user_id", "BIGINT UNSIGNED FK", "FK → users.id"],
          ["action", "VARCHAR(100)", "Jenis aksi: LOGIN, LOGOUT, CREATE_NASABAH, UPDATE_REKENING, dll."],
          ["model_type", "VARCHAR(100) NULL", "Nama model yang diubah: App\\Models\\Nasabah"],
          ["model_id", "BIGINT NULL", "ID record yang diubah"],
          ["old_values", "JSON NULL", "Data sebelum perubahan"],
          ["new_values", "JSON NULL", "Data setelah perubahan"],
          ["ip_address", "VARCHAR(45)", "IP address user"],
          ["user_agent", "TEXT NULL", "Browser/device info"],
          ["created_at", "TIMESTAMP", "Waktu log"],
        ],
        [1800, 2200, 3800]
      ),
      spacer(),

      pageBreak(),

      // ===== 7. API ENDPOINTS =====
      heading1("7. RANCANGAN API ENDPOINTS"),
      heading2("7.1 Daftar Endpoint Utama"),
      makeTable(
        ["Method", "Endpoint", "Fungsi", "Role"],
        [
          // Auth
          ["POST", "/api/auth/login", "Login user", "Public"],
          ["POST", "/api/auth/logout", "Logout user", "Authenticated"],
          // Nasabah
          ["GET", "/api/nasabah", "Daftar semua nasabah (paginated + filter)", "CS, BO, Manager, Admin"],
          ["POST", "/api/nasabah", "Tambah nasabah baru", "CS, Admin"],
          ["GET", "/api/nasabah/{id}", "Detail nasabah by ID", "CS, BO, Manager, Admin"],
          ["PUT", "/api/nasabah/{id}", "Update data nasabah", "CS, Admin"],
          ["DELETE", "/api/nasabah/{id}", "Nonaktifkan nasabah (soft delete)", "Admin"],
          ["GET", "/api/nasabah/search?q=", "Cari nasabah by nama/NIK/telp", "CS, BO, Manager, Admin"],
          // Rekening
          ["GET", "/api/rekening", "Daftar rekening (filter nasabah_id)", "CS, BO, Admin"],
          ["POST", "/api/rekening", "Buka rekening baru", "CS, BO, Admin"],
          ["GET", "/api/rekening/{id}", "Detail rekening", "CS, BO, Admin"],
          ["PATCH", "/api/rekening/{id}/status", "Update status rekening", "BO, Admin"],
          // Transaksi
          ["GET", "/api/transaksi", "Daftar transaksi (filter)", "CS, Manager, Admin"],
          ["POST", "/api/transaksi", "Catat transaksi baru", "CS, Admin"],
          ["GET", "/api/transaksi/{id}", "Detail transaksi + cetak slip", "CS, Manager, Admin"],
          // Kredit
          ["GET", "/api/kredit", "Daftar kredit", "BO, Manager, Admin"],
          ["POST", "/api/kredit", "Input kredit baru", "BO, Admin"],
          ["GET", "/api/kredit/{id}", "Detail kredit + jadwal angsuran", "BO, Manager, Admin"],
          ["PATCH", "/api/kredit/{id}", "Update kredit", "BO, Admin"],
          // Laporan
          ["GET", "/api/laporan/nasabah", "Generate laporan nasabah (PDF/Excel)", "Manager, Admin, Auditor"],
          ["GET", "/api/laporan/transaksi", "Generate laporan transaksi", "Manager, Admin, Auditor"],
          ["GET", "/api/laporan/kredit", "Generate laporan kredit", "Manager, Admin, Auditor"],
          // Users
          ["GET", "/api/users", "Daftar user", "Admin"],
          ["POST", "/api/users", "Tambah user baru", "Admin"],
          ["PUT", "/api/users/{id}", "Update user & role", "Admin"],
        ],
        [800, 2800, 2400, 1800]
      ),
      spacer(),

      pageBreak(),

      // ===== 8. MILESTONES =====
      heading1("8. ROADMAP PENGEMBANGAN (Milestone)"),
      makeTable(
        ["Fase", "Durasi", "Deliverable", "Fitur Utama"],
        [
          ["Fase 1: Foundation", "2 minggu", "Setup project, DB, Auth", "Setup repo, migrasi DB, login/logout, manajemen user & role"],
          ["Fase 2: Core Nasabah", "3 minggu", "Modul nasabah lengkap", "CRUD nasabah, pencarian, validasi NIK, duplikasi check"],
          ["Fase 3: Rekening & Transaksi", "3 minggu", "Modul rekening & transaksi", "CRUD rekening, catat transaksi, slip, riwayat"],
          ["Fase 4: Kredit", "2 minggu", "Modul kredit & angsuran", "Input kredit, jadwal amortisasi, status kolektibilitas"],
          ["Fase 5: Laporan & Dashboard", "2 minggu", "Laporan + Dashboard", "Dashboard stats, laporan PDF/Excel, export data"],
          ["Fase 6: Security & Testing", "1 minggu", "Sistem siap deploy", "Security audit, UAT, bug fixing, dokumentasi"],
        ],
        [1400, 1200, 2200, 3000]
      ),
      spacer(),

      pageBreak(),

      // ===== 9. ACCEPTANCE CRITERIA =====
      heading1("9. KRITERIA PENERIMAAN (Acceptance Criteria)"),
      makeTable(
        ["No", "Kriteria", "Metode Verifikasi"],
        [
          ["1", "User dapat login dengan username & password yang valid", "Test login sukses dan gagal"],
          ["2", "Petugas CS dapat input data nasabah baru dalam < 5 menit", "Observasi & timing test"],
          ["3", "Sistem menolak duplikasi NIK yang sama", "Test input 2 nasabah dengan NIK identik"],
          ["4", "Pencarian nasabah menampilkan hasil dalam < 2 detik", "Performance test dengan 500+ data"],
          ["5", "Laporan nasabah dapat di-download dalam format PDF & Excel", "Unduh & verifikasi isi file"],
          ["6", "Petugas dengan role CS tidak dapat mengakses menu Laporan Manager", "Login sebagai CS, coba akses laporan"],
          ["7", "Semua aktivitas CRUD tercatat di log sistem", "Cek tabel activity_logs setelah aksi"],
          ["8", "Sistem tidak dapat diakses tanpa login (unauthorized redirect)", "Akses URL langsung tanpa token"],
          ["9", "Data nasabah yang dihapus (soft delete) tidak muncul di daftar aktif", "Nonaktifkan nasabah, cek daftar"],
          ["10", "Backup otomatis berjalan setiap hari", "Verifikasi file backup di server"],
        ],
        [500, 3500, 2800]
      ),
      spacer(),

      spacer(),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 400 },
        border: { top: { style: BorderStyle.SINGLE, size: 4, color: "1A5276", space: 1 } },
        children: [new TextRun({ text: "— Akhir Dokumen PRD v1.0 —", size: 20, color: "888888", italics: true })]
      }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 60 },
        children: [new TextRun({ text: "Sistem Informasi Pengelolaan Data Nasabah Bank BTN Cabang Pekanbaru", size: 18, color: "1A5276" })]
      }),
    ]
  }]
});

Packer.toBuffer(doc).then(buffer => {
    fs.writeFileSync('C:\\laragon\\www\\PengolahanDataBanking\\PRD.docx', buffer);
  console.log('PRD berhasil dibuat!');
});