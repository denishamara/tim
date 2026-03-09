<?php

namespace App\Models;

use CodeIgniter\Model;

class RincianBiayaModel extends Model
{
    protected $table            = 'rincian_biaya';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'perjalanan_id', 'jenis_biaya_id', 'judul', 'keterangan', 'kendaraan_id',
        'uraian', 'qty', 'satuan', 'harga', 'total'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByPerjalanan($perjalananId)
    {
        return $this->select('rincian_biaya.*, kendaraan.nama_kendaraan, kendaraan.nomor_polisi, jenis_biaya.nama AS nama_jenis_biaya')
                    ->join('kendaraan',   'kendaraan.id = rincian_biaya.kendaraan_id',     'left')
                    ->join('jenis_biaya', 'jenis_biaya.id = rincian_biaya.jenis_biaya_id', 'left')
                    ->where('perjalanan_id', $perjalananId)
                    ->findAll();
    }

    public function getTotalByPerjalanan($perjalananId)
    {
        return $this->selectSum('total')
                    ->where('perjalanan_id', $perjalananId)
                    ->first()['total'] ?? 0;
    }

    /**
     * Return total per jenis biaya for a given perjalanan — useful for reports.
     * [ ['nama_jenis_biaya' => 'BBM PP', 'subtotal' => 350000], ... ]
     */
    public function getTotalPerJenis($perjalananId): array
    {
        return $this->db->table('rincian_biaya rb')
                        ->select('COALESCE(jb.nama, rb.judul) AS nama_jenis_biaya, SUM(rb.total) AS subtotal')
                        ->join('jenis_biaya jb', 'jb.id = rb.jenis_biaya_id', 'left')
                        ->where('rb.perjalanan_id', $perjalananId)
                        ->groupBy('rb.jenis_biaya_id, rb.judul')
                        ->orderBy('subtotal', 'DESC')
                        ->get()
                        ->getResultArray();
    }
}
