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
}
