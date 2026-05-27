<?php
class Angsuran extends Model {
    protected $table = 'angsuran';

    public function getByKredit(int $kreditId): array {
        $stmt = $this->db->prepare("SELECT * FROM angsuran WHERE kredit_id = ? ORDER BY periode_ke ASC");
        $stmt->execute([$kreditId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateJadwal(int $kreditId, float $plafon, float $sukuBunga, int $tenor, string $tanggalMulai): void {
        $bungaBulanan = $sukuBunga / 100 / 12;
        $angsuranPerBulan = $plafon * ($bungaBulanan * pow(1 + $bungaBulanan, $tenor)) / (pow(1 + $bungaBulanan, $tenor) - 1);
        $sisaPokok = $plafon;

        for ($i = 1; $i <= $tenor; $i++) {
            $bunga = $sisaPokok * $bungaBulanan;
            $pokok = $angsuranPerBulan - $bunga;
            $sisaPokok -= $pokok;

            $jatuhTempo = date('Y-m-d', strtotime("+$i months", strtotime($tanggalMulai)));
            
            $this->insert([
                'kredit_id' => $kreditId,
                'periode_ke' => $i,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'pokok' => round($pokok, 2),
                'bunga' => round($bunga, 2),
                'total_angsuran' => round($angsuranPerBulan, 2),
                'status' => 'belum_bayar',
            ]);
        }
    }
}
