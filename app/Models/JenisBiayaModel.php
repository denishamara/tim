<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisBiayaModel extends Model
{
    protected $table            = 'jenis_biaya';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nama', 'satuan_default', 'harga_default',
        'keterangan', 'butuh_kendaraan', 'aktif',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Return only active records. */
    public function getAktif(): array
    {
        return $this->where('aktif', 1)->orderBy('nama', 'ASC')->findAll();
    }

    /** Return all as id => nama array for dropdowns. */
    public function getDropdown(): array
    {
        $rows = $this->getAktif();
        $out  = [];
        foreach ($rows as $r) {
            $out[$r['id']] = $r['nama'];
        }
        return $out;
    }
}
