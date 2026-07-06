<?php
class Helper {
    public static function generateNoNasabah(): string {
        $db = Database::getInstance()->getConnection();
        $year = date('Y');
        $stmt = $db->prepare("SELECT COUNT(*) FROM nasabah WHERE YEAR(created_at) = ?");
        $stmt->execute([$year]);
        $count = (int)$stmt->fetchColumn();
        $urutan = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return "NAS-{$year}-{$urutan}";
    }

    public static function generateNoRekening(): string {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM rekening");
        $count = (int)$stmt->fetchColumn();
        $urutan = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
        return "00123-01-50-{$urutan}";
    }

    public static function generateNoTransaksi(): string {
        $db = Database::getInstance()->getConnection();
        $date = date('Ymd');
        $stmt = $db->prepare("SELECT COUNT(*) FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()");
        $stmt->execute();
        $count = (int)$stmt->fetchColumn();
        $urutan = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return "TRX-{$date}-{$urutan}";
    }

    public static function generateNoKredit(string $jenis): string {
        $db = Database::getInstance()->getConnection();
        $year = date('Y');
        $prefix = strtoupper(substr($jenis, 0, 3));
        $stmt = $db->prepare("SELECT COUNT(*) FROM kredit WHERE YEAR(created_at) = ?");
        $stmt->execute([$year]);
        $count = (int)$stmt->fetchColumn();
        $urutan = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$year}-{$urutan}";
    }

    public static function formatTanggal(?string $date): string {
        if (!$date) return '-';
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $ts = strtotime($date);
        return date('d', $ts) . ' ' . $months[(int)date('m', $ts) - 1] . ' ' . date('Y', $ts);
    }

    public static function formatRupiah($amount): string {
        return 'Rp ' . number_format((float)$amount, 0, ',', '.');
    }

    public static function sanitize(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(): void {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(419);
            die('CSRF token tidak valid. Silakan refresh halaman.');
        }
    }

    public static function logActivity(string $action, string $modelType = '', $modelId = 0, array $oldValues = [], array $newValues = []): void {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, model_type, model_id, old_values, new_values, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            Auth::id(),
            $action,
            $modelType ?: null,
            $modelId ?: null,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }

    public static function paginate(int $total, int $page, int $perPage): array {
        $lastPage = max(1, (int)ceil($total / $perPage));
        return [
            'total' => $total,
            'current_page' => min($page, $lastPage),
            'per_page' => $perPage,
            'last_page' => $lastPage,
            'from' => ($page - 1) * $perPage + 1,
            'to' => min($page * $perPage, $total),
        ];
    }

    public static function getStatusBadge(string $status): string {
        $colors = [
            'aktif' => 'bg-green-100 text-green-800',
            'nonaktif' => 'bg-gray-100 text-gray-800',
            'blacklist' => 'bg-red-100 text-red-800',
            'beku' => 'bg-yellow-100 text-yellow-800',
            'tutup' => 'bg-red-100 text-red-800',
            'sukses' => 'bg-green-100 text-green-800',
            'batal' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'lancar' => 'bg-green-100 text-green-800',
            'perhatian_khusus' => 'bg-yellow-100 text-yellow-800',
            'kurang_lancar' => 'bg-orange-100 text-orange-800',
            'diragukan' => 'bg-red-100 text-red-700',
            'macet' => 'bg-red-100 text-red-800',
            'lunas' => 'bg-blue-100 text-blue-800',
            'belum_bayar' => 'bg-yellow-100 text-yellow-800',
            'terlambat' => 'bg-red-100 text-red-800',
        ];
        $class = $colors[$status] ?? 'bg-gray-100 text-gray-800';
        $label = ucfirst(str_replace('_', ' ', $status));
        return "<span class=\"px-2 py-1 rounded-full text-xs font-medium {$class}\">{$label}</span>";
    }

    public static function getNasabahSegmentBadge(string $segmentKey, ?string $segmentLabel = null): string {
        $colors = [
            'mass' => 'bg-gray-100 text-gray-800',
            'prima' => 'bg-blue-100 text-blue-800',
            'prospera' => 'bg-green-100 text-green-800',
            'prioritas' => 'bg-yellow-100 text-yellow-800',
            'private' => 'bg-rose-100 text-rose-800',
        ];

        $labels = [
            'mass' => 'Mass Segment',
            'prima' => 'BTN Prima',
            'prospera' => 'BTN Prospera',
            'prioritas' => 'BTN Prioritas',
            'private' => 'BTN Private',
        ];

        $class = $colors[$segmentKey] ?? 'bg-gray-100 text-gray-800';
        $label = $segmentLabel ?: ($labels[$segmentKey] ?? ucfirst(str_replace('_', ' ', $segmentKey)));

        return "<span class=\"px-2 py-1 rounded-full text-xs font-medium {$class}\">{$label}</span>";
    }
}
