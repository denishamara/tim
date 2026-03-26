<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanModel extends Model
{
    protected $table            = 'kendaraan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nama_kendaraan', 'nomor_polisi', 'jenis', 'keterangan', 'aktif',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAktif()
    {
        return $this->where('aktif', 1)->orderBy('nama_kendaraan', 'ASC')->findAll();
    }

    public function getDropdown()
    {
        $list = $this->getAktif();
        $result = [];
        foreach ($list as $k) {
            $result[$k['id']] = $k['nama_kendaraan'] . ' (' . $k['nomor_polisi'] . ')';
        }
        return $result;
    }

    /**
     * Return array of kendaraan IDs that are already used in another perjalanan
     * whose date range overlaps with [$tglMulai, $tglSelesai].
     * Excludes the current perjalanan ($excludePerjalananId) and
     * ignores perjalanan with status draft/rejected.
     */
    public function getBusyKendaraanIds(string $tglMulai, string $tglSelesai, int $excludePerjalananId): array
    {
        $rows = $this->db->table('rincian_biaya rb')
            ->select('rb.kendaraan_id')
            ->join('perjalanan_dinas pd', 'pd.id = rb.perjalanan_id')
            ->where('rb.kendaraan_id IS NOT NULL', null, false)
            ->where('rb.perjalanan_id !=', $excludePerjalananId)
            ->whereNotIn('pd.status', ['draft', 'rejected_1', 'rejected_2'])
            ->where('pd.tanggal_berangkat <=', $tglSelesai)
            ->where('pd.tanggal_pulang >=', $tglMulai)
            ->groupBy('rb.kendaraan_id')
            ->get()->getResultArray();

        return array_column($rows, 'kendaraan_id');
    }
}
