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
        'perjalanan_id', 'judul', 'keterangan', 'kendaraan_id',
        'uraian', 'qty', 'satuan', 'harga', 'total'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByPerjalanan($perjalananId)
    {
        return $this->select('rincian_biaya.*, kendaraan.nama_kendaraan, kendaraan.nomor_polisi')
                    ->join('kendaraan', 'kendaraan.id = rincian_biaya.kendaraan_id', 'left')
                    ->where('perjalanan_id', $perjalananId)
                    ->findAll();
    }

    public function getTotalByPerjalanan($perjalananId)
    {
        return $this->selectSum('total')
                    ->where('perjalanan_id', $perjalananId)
                    ->first()['total'] ?? 0;
    }
}
